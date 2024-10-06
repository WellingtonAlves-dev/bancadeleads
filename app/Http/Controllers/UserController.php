<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Saldos;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = new User();
        if($request->search) {
            $users = $users->where("name", "like", "%{$request->search}%")
                ->orWhere("email", "like", "%{$request->search}%");
        }
        $users = $users
            ->orderBy("id", "desc")
            ->paginate(20);
        return view("pages.users", [
            "users" => $users
        ]);
    }
    public function novoView() {
        return view("pages.formUsers");
    }
    public function editarView($id) {
        $user = User::findOrFail($id);
        $saldos = Saldos::where("user_id", $id)
            ->orderBy("id", "desc")
            ->paginate(10);
        $saldoReposicao = Saldos::saldoReposicaoByUser($id);
        return view("pages.formUsers", [
            "user" => $user,
            "saldos" => $saldos,
            "saldoReposicao" => $saldoReposicao
        ]);
    }

    public function salvar(Request $request, int|null $id = null) {
        $data = $request->validate([
            "ativo" => "",
            "email_verificado" => "",
            "name" => "required",
            "email" => "required|email",
            "telefone" => "required",
            "role" => "required",
            "observacao" => "",
            "password" => $id == null ? "required" : ""
        ]);
        $data["email_verified_at"] = empty($data["email_verificado"]) ? null : Carbon::now();
        unset($data["email_verificado"]); 
        $data["name"] = strtolower($data["name"]);
        $data["ativo"] = !empty($data["ativo"]) ? true : false;
        if(empty($data["password"])) {
            unset($data["password"]);
        } else {
            $data["password"] = bcrypt($data["password"]);
        }
        if($id == null) {
            $user = User::create($data);
        } else {
            $user = User::where("id",$id)->update($data);
            $user = User::find($id);
        }
        if(!$user) {
            throw new \Exception("Não foi possível salvar o usuário. Entre em contato com o suporte");
        }
        return redirect("admin/users/editar/".$user->id)->with("success_save", "Usuário salvo com sucesso");
    }

    public function novoSaldo($user, Saldos $saldo ,Request $request) {
        $data = $request->validate([
            "valor" => "required",
            "tipo" => "required",
            "observacao" => ""
        ]);
        $data["valor"] = str_replace(".", "", $data["valor"]);
        $data["valor"] = str_replace(",", ".", $data["valor"]);
        if($data["tipo"] == "entrada") {
            $saldo->gerarEntrada($user, $data["valor"], $data["observacao"]);        
        } else {
            $saldo->gerarSaida($user, $data["valor"], $data["observacao"]);
        }

        return redirect("admin/users/editar/".$user)->with("success_save", "Saldo adicionado com sucesso");
    }

    public function novoSaldoReposicao(Request $request) {
        $id_user = $request->id_user;
        $valor = $request->valor;

        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);

        Saldos::atualizarSaldoReposicaoByUser($id_user, $valor);
        return redirect("admin/users/editar/".$id_user)->with("success_save", "Saldo de reposição alterado com sucesso");
    }

    public function excluir($id) {
        try {
            User::where("id", $id)->delete();
            return response("Corretor excluido com sucesso");
        } catch(Exception $e) {
            echo $e->getMessage();
            return response("Não foi possível excluir o corretor", 500);
        }
    }

    public function loginById($id) {
        $id_user_master = Auth::user()->id;
        Auth::loginUsingId($id);
        session(["user_master" => $id_user_master]);
        return redirect("/leads");
    }

    public function retornarConta() {
        $id_user_master = session("user_master");
        if($id_user_master) {
            Auth::loginUsingId($id_user_master);
            session()->forget("user_master");
        }
        return redirect("/leads");
    }

}
