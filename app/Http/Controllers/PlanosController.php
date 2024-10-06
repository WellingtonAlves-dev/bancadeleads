<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Planos;
use Illuminate\Http\Request;

class PlanosController extends Controller
{
    public function index() {
        return view("pages.planos", [
            "planos" => Planos::all()
        ]);
    }
    public function novoView() {
        return view("pages.formPlanos");
    }
    public function editarView($id) {
        $plano = Planos::findOrFail($id);
        return view("pages.formPlanos", [
            "plano" => $plano
        ]);
    }
    public function salvar(Request $request, int|null $id = null) {
        $data = $request->validate([
            "ativo" => "",
            "nome" => "required",
            "telefone" => "",
            "email" => "",
            "logo" => ""
        ]);

        $fileNameToStore = 'noimage.png';

        if($request->hasFile('logo')){
            // Get filename with the extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $request->file('logo')->storeAs('public/', $fileNameToStore);
        }

        $data["nome"] = ucwords($data["nome"]);
        $data["ativo"] = !empty($data["ativo"]) ? true : false;
        $data["logo"] = $fileNameToStore;
        if($id == null) {
            $plano = Planos::create($data);
        } else {
            $plano = Planos::where("id",$id)->update($data);
            $plano = Planos::find($id);
        }
        if(!$plano) {
            throw new \Exception("Não foi possível salvar o plano. Entre em contato com o suporte");
        }
        return redirect("admin/planos/editar/".$plano->id)->with("success_save", "Plano salvo com sucesso");
    }
}
