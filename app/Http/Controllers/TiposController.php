<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tipos;
use Illuminate\Http\Request;

class TiposController extends Controller
{
    public function index() {
        $tipos = Tipos::all();
        return view("pages.tipos",
        [
            "tipos" => $tipos
        ]
    );
    }
    public function novoView() {
        return view("pages.formTipos");
    }
    public function editarView($id) {
        $tipo = Tipos::findOrFail($id);
        return view("pages.formTipos", [
            "tipo" => $tipo
        ]);
    }
    public function salvar(Request $request, int|null $id = null) {
        $data = $request->validate([
            "ativo" => "",
            "nome" => "required",
            "preco_fria" => ""
        ]);
        $data["nome"] = ucwords($data["nome"]);
        $data["ativo"] = !empty($data["ativo"]) ? true : false;

        if(!empty($data["preco_fria"])) {
            $data["preco_fria"] = str_replace(".", "", $data["preco_fria"]);
            $data["preco_fria"] = str_replace(",", ".", $data["preco_fria"]);
        }

        if($id == null) {
            $tipo = Tipos::create($data);
        } else {
            $tipo = Tipos::where("id",$id)->update($data);
            $tipo = Tipos::find($id);
        }
        if(!$tipo) {
            throw new \Exception("Não foi possível salvar o tipo. Entre em contato com o suporte");
        }
        return redirect("admin/tipos/editar/".$tipo->id)->with("success_save", "Tipo salvo com sucesso");
    }
}
