<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NotificacoesPagamento;
use Illuminate\Http\Request;

class NotificacoesPagamentoController extends Controller
{
    public function index(Request $request) {
        $notificacoes = NotificacoesPagamento::select("notificacoes_pagamentos.*", "users.name as user_name")
            ->leftJoin("users", "users.id", "notificacoes_pagamentos.user_id")
            ->orderBy("notificacoes_pagamentos.id", "desc");
        if($request->id) {
            $notificacoes = $notificacoes->where("preference_id", $request->id)
                ->orWhere("id_payment", $request->id);
        }
        $notificacoes = $notificacoes->paginate(10);
        return view("pages.notificacoes", compact("notificacoes"));
    }
}
