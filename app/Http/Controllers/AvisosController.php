<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Avisos;
use Illuminate\Http\Request;

class AvisosController extends Controller
{
    public function index() {
        $avisos = Avisos::first();
        return view("pages.formAvisos", compact("avisos"));
    }
    public function save(Request $request) {
        $avisoForm = $request->aviso;
        $cor = "#fff";
        $ativo = $request->ativo ? true : false;
        
        $aviso = Avisos::first();
        if(!$aviso) {
            Avisos::create([
                "aviso" => $avisoForm,
                "cor" => $cor,
                "ativo" => $ativo
            ]);
        } else {
            $aviso->ativo = $ativo;
            $aviso->aviso = $avisoForm;
            $aviso->cor = $cor;
            $aviso->save();
        }

        return redirect()->back()->with("success_save", "Aviso salvo com sucesso");
    }
}
