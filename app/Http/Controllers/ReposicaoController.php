<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\MinhasLeads;
use App\Models\Reposicao;
use App\Models\Saldos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReposicaoController extends Controller
{
    public function gerarReposicao(Request $request, MinhasLeads $minhasLeadsModel) {
        $currentUser = Auth::user();
        $currentLeadID = $request->id_lead;
        $description = $request->descricao;

        if($currentUser->role != "user") {
            return response([
                "erro" => true,
                "msg" => "O usuário precisa ter a permissão user"
            ]);
        }
        //validar se a lead existe;
        $currentMinhaLead = $minhasLeadsModel->where("id_lead", $currentLeadID)
            ->where("id_user", $currentUser->id)->first();
        if(empty($currentMinhaLead)) {
            return response([
                "erro" => true,
                "msg" => "Está lead não existe"
            ]);
        }

        $LEAD = Lead::where("id", $currentLeadID)->first();

        //validar já a lead está no prazo de reposição
        $blockedReposicao = strtotime(date("Y-m-d H:i:s")) > strtotime($currentMinhaLead->created_at . " + 48 hours");
        if($blockedReposicao) {
            return response([
                "erro" => true,
                "msg" => "A lead já ultrapassou o prazo para a contestação"
            ]);
        }
        //verificar se existe na lista de reposição
        $validateReposicao = Reposicao::where("solicitante", $currentUser->id)
            ->where("lead_id", $currentLeadID)
            ->first();
        if(!empty($validateReposicao)) {
            return response([
                "erro" => true,
                "msg" => "Já foi solicitado uma contestação para essa lead"
            ]);
        }
        // Gerar reposição
        try {
            $reposicao = new Reposicao();
            $reposicao->solicitante = $currentUser->id;
            $reposicao->lead_id = $currentLeadID;
            $reposicao->descricao = $description;
            $reposicao->save();

            $saldoReposicaoAtual = Saldos::saldoReposicaoByUser($currentUser->id);
            if($saldoReposicaoAtual === null) {
                $saldoReposicaoAtual = 0;
            }
            $saldoReposicaoAtual = $saldoReposicaoAtual - $LEAD->preco;
            Saldos::atualizarSaldoReposicaoByUser($currentUser->id, $saldoReposicaoAtual);

            return response([
                "erro" => false,
                "msg" => "Sua contestação foi realizada."
            ]);

        } catch(Exception $e) {
            return response([
                "erro" => true,
                "msg" => $e->getMessage()
            ]);
        }
    }

    public function index(Reposicao $reposicoes) {
        $reposicoes = $reposicoes
            ->where("solicitante", Auth::user()->id)
            ->orderBy("reposicaos.id", "desc")
            ->paginate(10);
        return view("pages.reposicoesSolicitadas", compact("reposicoes"));
    }
    public function indexAdmin(Request $request, Reposicao $reposicao) {
    
        $reposicoes = $reposicao
            ->select("reposicaos.*", "users.name as user_name")
            ->join("users", "users.id", "reposicaos.solicitante")
            ->orderBy("reposicaos.id", "desc");

        $TAB = $request->tab;
        if($TAB) {
            $reposicoes = $reposicoes->where("status", $TAB);
        }

        $reposicoes = $reposicoes->paginate(10);
        return view("pages.reposicoes", compact("reposicoes"));
    }

    public function rejeitar(Request $request) {
        try {
            $id_reposicao = $request->id_reposicao;
            $motivo_reprovacao = $request->motivo_reprovacao;

            $reposicao = Reposicao::where("id", $id_reposicao)->first();
            $reposicao->status = "rejeitada";
            $reposicao->motivo_reprovacao = $motivo_reprovacao;
            $reposicao->save();    
        } catch(Exception $e) {
            return response()
                ->json([
                    "erro" => true,
                    "msg" => $e->getMessage()    
                ]); 
        }
        return response()->json([
            "erro" => false,
            "msg" => "contestação rejeitada com sucesso"
        ]);
    }

    public function aprovar(Request $request) {
        try {
            $id_reposicao = $request->id_reposicao;
            $reposicao = Reposicao::where("id", $id_reposicao)->first();
            $reposicao->status = "aprovada";
            $reposicao->save();  

            // adicionar o saldo com o valor da Lead
            $saldos = new Saldos();
            $lead = Lead::where("id", $reposicao->lead_id)->first();    
            $saldos->gerarEntradaReposicao($reposicao->solicitante, $lead->preco, "ESTORNO DA LEAD # ". $reposicao->lead_id);

            //remover essa lead da página "Minhas Leads"
            MinhasLeads::where("id_lead", $reposicao->lead_id)->delete();
        } catch(Exception $e) {
            return response()
                ->json([
                    "erro" => true,
                    "msg" => $e->getMessage()    
                ]); 
        }

        return response()->json([
            "erro" => false,
            "msg" => "contestação aprovada com sucesso"
        ]);
    }
}
