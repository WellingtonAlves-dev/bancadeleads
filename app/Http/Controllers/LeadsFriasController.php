<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\LeadsFriasImport;
use App\Models\Avisos;
use App\Models\Lead;
use App\Models\MinhasLeads;
use App\Models\Planos;
use App\Models\Saldos;
use App\Models\Tipos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LeadsFriasController extends Controller
{
    
    public function index(Request $request) {
        $avisos = Avisos::where("ativo", true)->first();
        $tipos = Tipos::where("ativo", true)->get();
        $planos = Planos::where("ativo", true)->get();
        return view("pages.leadsfrias", compact("tipos", "avisos", "planos"));
    }

    public function search(Request $request) {
        $leadsController = new LeadsController();
        $TOTAL_LEADS_PAGINA = 20;
        
        $leads = new Lead();
        $leads = $leads->select("leads.*", "tipos.preco_fria as preco","minhas_leads.id_user");
        $leads = $leads->join("tipos", "leads.tipo_id", "tipos.id");
        $leads = $leads->leftJoin("minhas_leads", "leads.id", "minhas_leads.id_lead");
        $leads = $leads->where("leads.ativo", true);
        $leads = $leads->whereNotNull("tipos.preco_fria");
        $leads = $leads->whereNull("minhas_leads.id");
        if($request->ddd && $request->ddd != "todos") {
            $leads = $leads->where("ddd", $request->ddd);
        }
        if($request->tipo && $request->tipo != "todos") {
            $leads = $leads->where("tipo_id", $request->tipo);
        }
        if($request->plano && $request->plano != "todos") {
            $leads = $leads->where("plano_id", $request->plano);
        }
        if($request->search && $request->search != "") {
            $leads = $leads->where("leads.id", $request->search)
                ->orWhere("leads.email", $request->search);
        }
        $dataHoje = date("Y-m-d H:i:s");
        $leads = $leads->whereNull("minhas_leads.id")
        ->whereRaw("ADDDATE(horario_partida, INTERVAL 7 DAY) < '{$dataHoje}'");
        $leads = $leads->orderBy("horario_partida", "desc");
        $totalLeads = $leads->count();
        $leads = $leads->skip($request->offset ?? 0);
        $leads = $leads->take($request->limit ?? $TOTAL_LEADS_PAGINA);
        $leads = $leads->get();
        $leads = $leadsController->formatarLeads($leads);
        if(Auth::user()->role !== "admin"){
            foreach($leads as $leadTmp) {
                if($leadTmp->disponivel == false) {
                    $totalLeads--;
                }
            }
        }
        $tipos = Tipos::where("ativo", true)->get();
        if(Auth::user()->role === "admin") {
            return view("components.table", compact("leads", "tipos", "totalLeads"));
        }
        return view("pages.lead_search_fria", compact("leads", "tipos", "totalLeads"));
    }


    public function comprarLeadModal($id_lead) {
        $lead = Lead::select("leads.*", "tipos.preco_fria as preco")
            ->join("tipos", "tipos.id", "leads.tipo_id")
            ->where("leads.id", $id_lead)
            ->whereNotNull("tipos.preco_fria")
            ->first();
        if(empty($lead)) {
            return response()->view("components.modal_alert", [
                "title" => "Lead não encontrada",
                "msg" => "Desculpe, essa lead não existe" 
            ])
            ->setStatusCode(404);
        }
        return view("components.comprarlead", [
            "lead" => $lead
        ]);
    }

    public function comprarLead($id_lead, Saldos $saldos) {
        $lead = Lead::select("leads.*", "tipos.preco_fria as preco")
            ->join("tipos", "tipos.id", "leads.tipo_id")
            ->where("leads.id", $id_lead)
            ->whereNotNull("tipos.preco_fria")
            ->first();
        if(empty($lead)) {
            return response()
                ->view("components.modal_alert",
                [
                    "title" => "Lead não encontrada",
                    "msg" => "Desculpe, essa lead não existe." 
                ])
                ->setStatusCode(400);
        }
        if(MinhasLeads::where("id_lead", $id_lead)->first()) {
            return response()
            ->view("components.modal_alert",
            [
                "title" => "Lead já vendida",
                "msg" => "Desculpe, mas essa lead já foi vendida." 
            ])
            ->setStatusCode(400);
        }
        if(Auth::user()->getValorSaldo() < $lead->preco) {
            return response()
            ->view("components.modal_alert",
            [
                "title" => "Saldo insuficiente",
                "msg" => "Desculpe, mas seu saldo é insuficiente.",
            ])
            ->setStatusCode(400);
        }

        // Retirar o valor do saldo
        $user_id = Auth::user()->id;
        $saldos->gerarSaida($user_id, $lead->preco, "COMPRA DE LEAD APROVADA # ". $lead->id, $lead->id, true);
        //cadastrar o lead na conta
        MinhasLeads::create([
            "id_lead" => $lead->id,
            "id_user" => $user_id 
        ]);
        
        return view("components.modal_alert",
        [
            "title" => "Parabéns!!!",
            "msg" => "Seu Lead foi comprado com Sucesso e está disponível em `Meus Leads`.",
            "alert_type" => "bg-success",
            "user_id" => $user_id,
            "user" => Auth::user()->name
        ]);
    }   

    public function importarLeads(Request $request) {
        $request->validate(["file" => "required|mimes:xlx,csv,xlsx"]);
        // $fileName = time().'.'.$request->file->extension();  
        $path1 = $request->file('file')->store('temp'); 
        $path=storage_path('app').'/'.$path1;          
        Excel::import(new LeadsFriasImport, $path);
        return redirect()->back()->with("success_save", "Leads frias importada com sucesso");
    }

}
