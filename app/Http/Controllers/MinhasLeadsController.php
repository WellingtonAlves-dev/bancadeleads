<?php

namespace App\Http\Controllers;

use App\Exports\ExportsLead;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadsCompartilhadas;
use App\Models\MinhasLeads;
use App\Models\Saldos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MinhasLeadsController extends Controller
{
    public function index(Request $request) {
        $minhasLeads = Lead::select(
            "leads.*", 
            "minhas_leads.created_at as dataAquisicao", 
            "reposicaos.id as reposicaoID", 
            "reposicaos.status as reposicaoStatus",
            "saldos.valor as preco", 
            "saldos.lead_fria as statusLeadFria"
        );
    
        // Lógica para junção com saldos apenas quando a origem não for 'importador'
        $minhasLeads = $minhasLeads->leftJoin("saldos", function($join) {
            $join->on("saldos.lead_id", "=", "leads.id")
                 ->where(function($query) {
                     $query->where("leads.origem", "!=", "importador")
                           ->orWhereNull("leads.origem");
                 });
        });
    
        // Junção com minhas_leads com lógica condicional
        if (!Auth::user()->vinculado) {
            $minhasLeads = $minhasLeads
                ->join("minhas_leads", function($join) {
                    $join->on("minhas_leads.id_lead", "=", "leads.id")
                         ->where("minhas_leads.id_user", "=", Auth::user()->id);
                });
        } else {
            $minhasLeads = $minhasLeads
                ->join("minhas_leads", function($join) {
                    $join->on("minhas_leads.id_lead", "=", "leads.id");
                })
                ->where("minhas_leads.id_user", Auth::user()->id);
        }
    
        // Junção com reposição
        $minhasLeads = $minhasLeads
            ->leftJoin("reposicaos", function($join) {
                $join->on("reposicaos.lead_id", "=", "leads.id")
                     ->where("reposicaos.solicitante", "=", Auth::user()->id);
            })
            ->orderBy("minhas_leads.id", "desc");
    
        // Debug da query se necessário
        if ($request->get("dd") == "welltech") {
            dd($minhasLeads->toSql());
        }
    
        // Paginação
        $paginador = $minhasLeads->paginate(25);
    
        // Mapear leads para incluir corretor
        $minhasLeads = $paginador->map(function($minhaLead) {
            $leadUser = MinhasLeads::where("id_user", "!=", Auth::user()->id)
                ->where("id_lead", $minhaLead->id)
                ->leftJoin("users", "users.id", "minhas_leads.id_user")
                ->first();
    
            $minhaLead->corretor = $leadUser->name ?? null;
            $minhaLead->corretor_id = $leadUser->id_user ?? null;
    
            return $minhaLead;
        });
    
        // Obter saldo de reposição
        $saldoReposicao = Saldos::saldoReposicaoByUser(Auth::user()->id);
    
        // Retorna a view com dados
        return view("pages.minhasleads", [
            "leads" => $minhasLeads,
            "paginador" => $paginador,
            "saldoReposicao" => $saldoReposicao
        ]);
    }
    
    public function excelDownload(Request $request) {
        return Excel::download(new ExportsLead($request->data_inicial, $request->data_final), "minhasleads.xlsx");
    }

    public function modalEnviarLead($lead_id) {
        $users = User::where("user_master", Auth::user()->id)
            ->where("ativo", true)
            ->get();

        $leadsusers = MinhasLeads::where("id_user", "!=", Auth::user()->id)
            ->join("users", "minhas_leads.id_user", "users.id")
            ->orderBy("id_user")
            ->get();

        return view("components.modal_enviarlead", compact("users", "lead_id", "leadsusers"));
    }

    public function remover($id_lead, $id_user) {
        //
        $verificarUsuarioAdm = MinhasLeads::where("id_user", Auth::user()->id)
            ->where("id_lead", $id_lead)
            ->first();  
        if(!$verificarUsuarioAdm) {
            return response()->json(["msg" => "error"]);
        }
        MinhasLeads::where("id_user", $id_user)
            ->where("id_lead", $id_lead)
            ->delete();
        return response()->json(["msg" => "success"]);
    }

    public function enviarLead(Request $request) {
        $lead_id = $request->lead;
        $user_id = Auth::user()->id;
        $corretor_id = $request->corretor;
        $corretor = User::where("id", $corretor_id)->first();
        $leadExiste = MinhasLeads::where("id_lead", $lead_id)
            ->where("id_user", $user_id)->first();
        
        

        if(empty($leadExiste) || empty($corretor)) {
            return response()->view("components.modal_alert", [
                "title" => "Não foi possível enviar a Lead",
                "msg" => "Entre em contato com um administrador"
            ])->setStatusCode(404);
        }

        //remover dos antigos corretores

        MinhasLeads::where("id_lead", $lead_id)
            ->where("id_user", "!=", Auth::user()->id)
            ->delete();

        // Validar se já existe
        $minhaLead = MinhasLeads::where("id_lead", $lead_id)
            ->where("id_user", $corretor_id)
            ->first();
        if(empty($minhaLead)) {
            MinhasLeads::create([
                "id_lead" => $lead_id,
                "id_user" => $corretor_id,
                "observacao" => "Lead transferida do usuário com o ID: ". $user_id
            ]);
        }
        return response()->view("components.modal_alert", [
            "title" => "Lead enviada com sucesso",
            "msg" => "Sua lead agora está disponível para o usuário " . $corretor->name,
            "alert_type" => "bg-success",
            "user" => $corretor->name,
            "user_id" => $corretor->id
        ]);
    }
}
