<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorretoresController extends Controller
{
    public function index() {
        $corretores = User::where("vinculado", true)
            ->where("user_master", Auth::user()->id)
            ->orderBy("id", "desc")
            ->get();

        return view("pages.corretores", compact("corretores"));
    }

    public function novoView() {
        return view("pages.formCorretor");
    }
    public function editarView($id) {
        $corretor = User::where("vinculado", true)
            ->where("id", $id)
            ->where("user_master", Auth::user()->id)
            ->first();
        if(empty($corretor)) {
            abort(404, "Não foi encontrado nenhum corretor vinculado com este ID");
        }
        return view("pages.formCorretor", compact("corretor"));
    }
    public function salvar(Request $request, int|null $id = null) {
        $data = $request->validate([
            "ativo" => "",
            "name" => "required",
            "email" => "required",
            "password" => $id == null ? "required|min:3" : ""
        ]);
        $data["name"] = ucwords($data["name"]);
        $data["ativo"] = !empty($data["ativo"]) ? true : false;
        if(empty($data["password"])) {
            unset($data["password"]);
        } else {
            $data["password"] = sha1($data["password"]);
        }

        $data["email_verified_at"] = Carbon::now();
        $data["vinculado"] = True;
        $data["user_master"] = Auth::user()->id;
        $data["role"] = "corretor";
        $data["telefone"] = Auth::user()->telefone;
        if($id == null) {
            $corretorExiste = User::where("email", $data["email"])->first();
            if($corretorExiste) {
                return redirect()->back()->withErrors([
                    "corretor_existente" => "E-mail já cadastrado"
                ]);
            }
            $corretor = User::create($data);
        } else {
            $corretor = User::where("id",$id)
                ->where("user_master", Auth::user()->id)
                ->update($data);
            $corretor = User::where("id", $id)
                ->where("user_master", Auth::user()->id)->first();
            if(empty($corretor)) {
                abort(404);
            }
        }
        if(!$corretor) {
            throw new \Exception("Não foi possível salvar o corretor. Entre em contato com o suporte");
        }
        return redirect("corretores/editar/".$corretor->id)->with("success_save", "Corretor salvo com sucesso");
    }


}
