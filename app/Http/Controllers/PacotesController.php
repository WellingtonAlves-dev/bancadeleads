<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\MinhasLeads;
use App\Models\Saldos;
use App\Models\Tipos;
use App\Models\Pacotes;
use App\Models\Planos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacotesController extends Controller
{
    public function adminIndexView() {
        $pacotes = Pacotes::all();
        return view("pages.adminPacotes", compact("pacotes"));
    }

    public function novoPacote() {
        return view("pages.formPacotes");
    }

    public function editarPacote(Request $request, $id) {
        $pacote = Pacotes::findOrFail($id);
        return view("pages.formPacotes", compact("pacote"));
    }
    
    public function salvarPacote(Request $request, $id = null) {
        $validar = $request->validate([
            "ativo" => "",
            "name" => "required",
            "qtd_leads" => "required",
            "qtd_desconto" => "required",
            "descricao" => ""
        ]);
        $validar["ativo"] = isset($validar["ativo"]) ? $validar["ativo"] == "on" : false; 
        $pacoteId = $id;
        if($id) {
            Pacotes::where("id", $id)->update($validar);
        } else {
            $pacoteId = Pacotes::create($validar)->id;
        }
        return redirect("admin/pacotes/editar/".$pacoteId)->with("success_save", "Pacote salvo com sucesso");
    }

    public function pacotesCorretor() {
        $pacotes = Pacotes::where("ativo", true)->get();
        $tipos = Tipos::where("ativo", true)->get();
        $planos = Planos::where("ativo", true)->get();
        return view("pages.comprarPacotes", compact("pacotes", "tipos", "planos"));
    }

    public function comprarPacote(Request $request) {
        $ids_leads = $request->ids_leads;
        $id_pacote = $request->id_pacote;

        if(is_array(!$ids_leads)) {
            return response()->json([
                "status" => false,
                "msg" => "É necessário escolher leads"
            ]);
        }

        $pacote = Pacotes::where("id", $id_pacote)->first();
        if(!isset($pacote)) {
            return response()->json([
                "status" => false,
                "msg" => "Não foi encontrado nenhum pacote com esse ID"
            ]);
        }

        if(!$pacote->ativo) {
            return response()->json([
                "status" => false,
                "msg" => "Infelizmente esse pacote não está ativo"
            ]);
        }

        if($pacote->qtd_leads != count($ids_leads)) {
            return response()->json([
                "status" => false,
                "msg" => "É necessário escolher $pacote->qtd_leads LEADS"
            ]);
        }
        // VERIFICAR SE O SALDO DO VENDEDOR É COMPATIVEL COM O TOTAL DE LEADS QUE ELE QUER COMPRAR + O DESCONTO
        
        $leadsSelecionadas = Lead::whereIn("id", $ids_leads)->get();
        $totalLeadSelecionada = 0;
        foreach($leadsSelecionadas as $leadSelecionada) {
            $totalLeadSelecionada += $leadSelecionada->preco;
        }
        $totalLeadSelecionadaComDesconto = $totalLeadSelecionada - (($totalLeadSelecionada * $pacote->qtd_desconto) / 100);
        if(Auth::user()->getValorSaldo() < $totalLeadSelecionadaComDesconto) {
            return response()->json([
                "status" => false,
                "msg" => "SALDO INSUFICIENTE"
            ]);
        }
        $leadsController = new LeadsController();
        foreach($ids_leads as $id_lead) {

            // verificar se a lead existe
            $lead = Lead::find($id_lead);
            if(empty($lead)) {
                return response()->json([
                    "status" => false,
                    "msg" => "LEAD $id_lead NÃO ENCONTRADA"
                ]);
            }

            // Verificar se a LEAD já foi vendida
            if(MinhasLeads::where("id_lead", $id_lead)->first()) {
                return response()
                ->json([
                    "status" => false,
                    "msg" => "DESCULPE, MAS ESSA LEAD JÁ FOI VENDIDA"
                ]);
            }

            if(MinhasLeads::where("id_lead", $id_lead)->first()) {
                return response()
                ->json([
                    "status" => false,
                    "msg" => "DESCULPE, MAS ESSA LEAD JÁ FOI VENDIDA"
                ]);
            }

            // Retirar o valor do saldo
            $user_id = Auth::user()->id;
            $leadPrecoDesconto = $lead->preco - (($lead->preco * $pacote->qtd_desconto) / 100);
            $saldos = new Saldos();
            $saldos->gerarSaida($user_id, $leadPrecoDesconto, "COMPRA DE LEAD APROVADA # ". $lead->id, $lead->id, false, true, $pacote->id);
            //cadastrar o lead na conta
            MinhasLeads::create([
                "id_lead" => $lead->id,
                "id_user" => $user_id 
            ]);
            // EXCLUIR LEAD EXTERNA
            try {
                if($lead->id_global) {
                    $leadsController->excluirLeadExterna($lead->id_global);
                }
            } catch(Exception $e) {
                // 
            }
        }


        return response()->json([
            "status" => true,
            "msg" => "SEU PACOTE DE LEAD FOI ADQUIRIDO COM SUCESSO"
        ]);

    }
}
