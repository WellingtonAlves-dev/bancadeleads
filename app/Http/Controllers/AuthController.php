<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmMail;
use App\Mail\RecoveryMail;
use App\Models\RecoveryPassword;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use User;

class AuthController extends Controller
{
    public function loginView() {
        if(Auth::check()) {
            return redirect("/");
        }
        return view("auth.login");
    }
    public function signUpView() {
        return view("auth.signup");
    }
    public function signup(Request $request) {
        $data = $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users,email",
            "telefone" => "required|min:15",
            "password" => "required|min:6"
        ]);
        $data["name"] = strtolower($data["name"]);
        $data["email"] = strtolower($data["email"]);
        $data["password"] = sha1($data["password"]);
        $data["email_verified_at"] = Carbon::now();
        $user = \App\Models\User::create($data);
        
        //e-mail de confirmação
        //Mail::to($data["email"])->send(new ConfirmMail($data["name"], $user->id));    

        return redirect("/login")->with("cadastro_sucesso", "Cadastro Realizado com sucesso");
    }
    public function login(Request $request) {
        $data = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);
        $user = ModelsUser::where(["email" => $data["email"]])
            ->where("ativo", true)
            ->first();
        if(!$user) {
            return redirect()->back()->with("nao_cadastrado", true);
        }

        if($user->email_verified_at === null) {
            return redirect()->back()->with("nao_verificado", true);
        }

        if(Hash::check($data["password"], $user->password)) {
            Auth::loginUsingId($user->id);
            return redirect("/");
        } else {
            return redirect()->back()->with("nao_cadastrado", true);
        }

    }
    public function verifyMail($id) {
        $user = ModelsUser::findOrFail($id);
        if($user->email_verified_at !== null) {
            return redirect("/login");
        }
        $user->email_verified_at = Carbon::now();
        $user->save();
        return redirect("/login")->with("mail_verify", "e-mail verificado");
    }
    public function logout() {
        Auth::logout();
        return redirect("/login");
    }
    
    public function recoveryPasswordView() {
        return view("auth.recovery_password");
    }

    public function sendcodeRecovery(Request $request) {
        $email = $request->email;
        $user = ModelsUser::where("email", $email)->first();
        if(!$user) {
            return redirect()->back()->with("success", "E-mail de recuperação enviado com sucesso");
        }
        //e-mail de recuperação
        //Mail::to($data["email"])->send(new ConfirmMail($data["name"], $user->id));    
        $unique_id=floor(time()-999999999);
        Mail::to($email)->send(new RecoveryMail($user->id, $user->name, $unique_id));
        RecoveryPassword::create([
            "user_id" => $user->id,
            "unique_code" => $unique_id
        ]);
        return redirect()->back()->with("success", "E-mail de recuperação enviado com sucesso");
    }

    public function newPasswordView($unique_code) {
        $recoveryData = RecoveryPassword::where("unique_code", $unique_code)->first();
        if(empty($recoveryData)) {
            return redirect("/login");
        }
        return view("pages.new_password", compact("unique_code"));   
    }

    public function newPassword(Request $request) {
        $unique_code = $request->unique_code;
        $recoveryPassword = RecoveryPassword::where("unique_code", $unique_code)->first();
        if(empty($recoveryPassword)) {
            return redirect("/login");
        }

        $password = sha1($request->senha);
        $user = ModelsUser::where("id", $recoveryPassword->user_id)->first();
        $user->password = $password;
        $user->save();
        $recoveryPassword->delete();
        return redirect("/login")->with("password_reset", "sim");
    }
}
