<?php

namespace App\Http\Controllers;

use App\Exports\ExportsLeadsAdmin;
use App\Http\Controllers\Controller;
use App\Imports\LeadsImport;
use App\Models\Avisos;
use App\Models\Lead;
use App\Models\MinhasLeads;
use App\Models\Planos;
use App\Models\Saldos;
use App\Models\Tipos;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LeadsController extends Controller
{
    public function index(Request $request) {
        $avisos = Avisos::where("ativo", true)->first();

        $tipos = Tipos::where("ativo", true)->get();
        $planos = Planos::where("ativo", true)->get();
        if(Auth::user()->role == "admin") {
            $leads = $this->getLeads($request);
            $paginator = $leads->paginate(20);
            $leads = $this->formatarLeads($paginator);
            return view("pages.leadsadmin", compact("tipos", "leads", "paginator"));
        }
        return view("pages.leads", compact("tipos", "avisos", "planos"));
    }
    public function search(Request $request) {
        $TOTAL_LEADS_PAGINA = 20;
        
        $leads = $this->getLeads($request);

        $totalLeads = $leads->count();

        $leads = $leads->skip($request->offset ?? 0);
        $leads = $leads->take($request->limit ?? $TOTAL_LEADS_PAGINA);
        $leads = $leads->get();

        $leads = $this->formatarLeads($leads);

        if(Auth::user()->role !== "admin"){
            foreach($leads as $leadTmp) {
                if($leadTmp->disponivel == false) {
                    $totalLeads--;
                }
            }
        }
        $tipos = Tipos::where("ativo", true)->get();
        $selecionavel = $request->get("selecionavel") ? true : false;
        if($selecionavel) {
            $leads = $leads->filter(function($lead) {
                if($lead->disponivel) {
                    return true;
                }
                return false;
            }); 
            $leads = $leads->map->only(["id", "cnpj", "plano", "tipo","ddd", "idade", "preco", "horario_partida", "disponivel"]);
            return response()->json(compact("leads", "tipos", "totalLeads"));
        }

        if(Auth::user()->role === "admin") {
            return view("components.table", compact("leads", "tipos", "totalLeads"));
        }
        return view("pages.lead_search", compact("leads", "tipos", "totalLeads", "selecionavel"));
    }

    public function getLeads(Request $request) {
        $leads = new Lead();
        $leads = $leads->select("leads.*", "minhas_leads.id_user", "users.name");
        $leads = $leads->leftJoin("minhas_leads", "leads.id", "minhas_leads.id_lead");
        $leads = $leads->leftJoin("users", "id_user", "users.id");
        $dataHoje = date("Y-m-d H:i:s");
        if(Auth::user()->role != "admin") {
            $userId = Auth::user()->id;
            $leads = $leads->where("leads.ativo", true);
            $leads = $leads->whereNull("minhas_leads.id")
                   ->whereNotExists(function($q) use ($userId) {
                       $q->select(DB::raw(1))
                         ->from("reposicaos as repo")
                         ->whereColumn("repo.lead_id", "leads.id") // mesma lead
                         ->where("repo.solicitante", $userId);    // mesmo usuário
                   });
        }
        if(is_array($request->ddd) && count($request->ddd) > 0) {
            $leads = $leads->whereIn("ddd", $request->ddd);
        }
        if(is_array($request->tipo) && count($request->tipo) > 0) {
            $leads = $leads->whereIn("tipo_id", $request->tipo);
        }
        if(is_array($request->plano) && count($request->plano) > 0) {
            $leads = $leads->whereIn("plano_id", $request->plano);
        }
        if($request->search && $request->search != "") {
            $leads = $leads->where("leads.id", $request->search)
                ->orWhere("leads.email", $request->search);
        }
        if($request->tab === "nao_vendidas") {
            $leads = $leads->whereNull("minhas_leads.id")
                ->whereRaw("ADDDATE(horario_partida, INTERVAL dias_disponivel DAY) >= '{$dataHoje}'");
        }
        if($request->tab === "vendidas") {
            $leads = $leads->whereNotNull("minhas_leads.id");
        }
        if($request->tab === "arquivadas") {
            $leads = $leads->whereNull("minhas_leads.id")
                ->whereRaw("ADDDATE(horario_partida, INTERVAL dias_disponivel DAY) < '{$dataHoje}'");
        }
        $leads = $leads->orderBy("horario_partida", "desc");
        return $leads;
    }

    public function formatarLeads($leads) {
        $dataAtual = Carbon::now();

        $leads = $leads->map(function($lead) use ($dataAtual) {
                
            $horario_partida_carbon = Carbon::parse($lead->horario_partida);
            $dataLimite = $horario_partida_carbon->addDays($lead->dias_disponivel);
            $lead->disponivel_ate = $dataLimite->format("d/m/y H:m:i");
            $lead->disponivel = $dataAtual->lt($dataLimite);
            //FORMATAR DATA
            $previousDate = Carbon::parse($lead->horario_partida);
            $diffInSeconds = $previousDate->diffInSeconds($dataAtual);
            $diffInDays = floor($diffInSeconds / (3600 * 24));
            $diffInHours = floor(($diffInSeconds % (3600 * 24)) / 3600);
            $diffInMinutes = floor(($diffInSeconds % 3600) / 60);
            
            $lead->horario_partida = "Adicionado há {$diffInDays}d {$diffInHours}h e {$diffInMinutes}m";
            $lead->preco = number_format($lead->preco, 2, ",");
            if($lead->qtd_vidas < 10 && $lead->qtd_vidas > 0) {
                $lead->qtd_vidas = "0{$lead->qtd_vidas}";
            }
            return $lead;
        });
        
        $leads = $leads->unique();
        return $leads;
    }

    public function novoView() {
        $planos = Planos::where("ativo", true)->get();
        $tipos = Tipos::where("ativo", true)->get();
        return view("pages.FormLeads", [
            "planos" => $planos,
            "tipos" => $tipos,
        ]);
    }
    public function editarView($id) {
        $lead = Lead::findOrFail($id);
        $planos = Planos::where("ativo", true)->get();
        $tipos = Tipos::where("ativo", true)->get();

        return view("pages.FormLeads", [
            "lead" => $lead,
            "planos" => $planos,
            "tipos" => $tipos
        ]);
    }
    public function salvar(Request $request, int|null $id = null) {
        $data = $request->validate([
            "ativo" => "",
            "preco" => "",
            "dias_disponivel" => "",
            "qtd_vidas" => "",
            "nome_lead" => "",
            "ddd" => "",
            "telefone" => "",
            "email" => "",
            "extra" => "",
            "plano_id" => "",
            "tipo_id" => "",
            "idade" => "",
            "cnpj" => ""
        ]);

        $data["nome_lead"] = ucwords($data["nome_lead"]);
        $data["ativo"] = !empty($data["ativo"]) ? true : false;
        $data["cnpj"] = !empty($data["cnpj"]) ? true : false;
        $data["qtd_vidas"] = 1;
        if($id == null) {
            $data["horario_partida"] = Carbon::now();
        }
        $data["preco"] = str_replace(".", "", $data["preco"]);
        $data["preco"] = str_replace(",", ".", $data["preco"]);
        if($id == null) {
            $lead = Lead::create($data);
        } else {
            $lead = Lead::where("id",$id)->update($data);
            $lead = Lead::find($id);
        }
        if(!$lead) {
            throw new \Exception("Não foi possível salvar o lead. Entre em contato com o suporte");
        }
        return redirect("admin/leads/editar/".$lead->id)->with("success_save", "Lead salvo com sucesso");
    }

    public function resetHorarioPartida($id) {
        $lead = Lead::findOrFail($id);
        $lead->horario_partida = Carbon::now();
        $lead->save();
        return redirect()->back()->with("success_save", "A lead teve o horario resetado com sucesso");
    }

    public function comprarLeadModal($id_lead) {
        $lead = Lead::find($id_lead);
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
        $lead = Lead::find($id_lead);
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
        $saldos->gerarSaida($user_id, $lead->preco, "COMPRA DE LEAD APROVADA # ". $lead->id, $lead->id);
        //cadastrar o lead na conta
        MinhasLeads::create([
            "id_lead" => $lead->id,
            "id_user" => $user_id 
        ]);

        try {
            if($lead->id_global) {
                $this->excluirLeadExterna($lead->id_global);
            }
        } catch(Exception $e) {
            //
        } 
        
        return view("components.modal_alert",
        [
            "title" => "Compra Efetuada!!",
            "msg" => "Agora você pode visualizar sua lead em `Minhas Leads`",
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
        Excel::import(new LeadsImport, $path);
        return redirect()->back()->with("success_save", "Leads importada com sucesso");
    }

    public function importarLeadsApi(Request $request) {
        $secretToken = $request->input("token");
        if($secretToken != "welltechparasempre") {
            return response("Não foi possível importar as leads");
        }
        $this->importLeadsJSON($request->post("rows"));
        return response("Leads importadas com sucesso");
    }

    public function exportarLeads(Request $request) {
        return Excel::download(new ExportsLeadsAdmin($request), "leads.xlsx");
    }

    public function toggleLeadStatus($id_lead, $status) {
        $lead = Lead::where("id", $id_lead)->update([
            "ativo" => $status == "ativo" ? true : false
        ]);
        if($lead) {
            return response("Lead atualizada com sucesso");
        }
        return response("", 500);
    }

    public function excluir($id_lead) {
        try {
            $lead = Lead::where("id", $id_lead)->delete();
            return response("Lead excluida com sucesso");
        } catch(Exception $e) {
            return response("Não foi possível excluir a lead $id_lead", 500);
        }
    }

    public function excluirApi(Request $request, $id_global) {
        try {
            if($request->get("token") != "welltechparasempre") {
                return response("Não existe esse token");
            }
            $lead = Lead::where("id_global", $id_global)->delete();
            return response("Lead excluida com sucesso");
        } catch(Exception $e) {
            return response("Não foi possível excluir a lead $id_global", 500);
        }
    }


    private function importLeadsJSON($rows) {
        foreach($rows as $row) {
            $telefone = $row["telefone"] ?? "";
            $ddd = 0;
            if($telefone != "") {
                $telefone = strval(preg_replace("/[^0-9]/", "", $telefone));
                $ddd = substr($telefone, 0, 2);
                $telefone = substr($telefone, 2);
                $telefone = substr($telefone, 0, 5) . "-" .substr($telefone, 5);
            }

            $preco = $row["preco"] ?? 0;
            if($preco != 0) {
                $preco = str_replace("R$", "", $preco);
                if(is_numeric($preco)) {
                    $preco = $preco - (($preco * 2) / 100);
                }
            } else {
                $preco = 9.5;
            }

            $plano = Planos::where("nome", "LIKE", '%'.$row["plano"].'%')->first();
            $plano_id = null;
            if($plano) {
                $plano_id = $plano->id;
            } else {
                $plano_id = Planos::create([
                    "ativo" => false,
                    "nome" => strtoupper($row["plano"]),
                    "telefone" => "",
                    "email" => ""
                ])->id;
            }

            $tipo_id = null;
            $tipo = Tipos::where("nome", "LIKE", '%'.$row["tipo"].'%')->first();
            if($tipo) {
                $tipo_id = $tipo->id;
            } else {
                $tipo_id = Tipos::create([
                    "nome" => $row["tipo"]
                ])->id;
            }

            $data = [
                "ativo" => false,
                "preco" => $preco,
                "dias_disponivel" => 1,
                "qtd_vidas" => 1,
                "nome_lead" => strtoupper($row["nome"]) ?? "NÃO INFORMADO",
                "ddd" => $ddd,
                "telefone" => $telefone,
                "email" => $row["email"] ?? "NÃO INFORMADO",
                "extra" => $row["adicionais"] ?? "NÃO INFORMADO",
                "plano_id" => $plano_id,
                "tipo_id" => $tipo_id,
                "idade" => $row["idade"] ?? "NÃO INFORMADO",
                "cnpj" => in_array(strtolower($row["cnpj"] ?? ""), ["não","nao", "n"]) == "Não" ? false : true,
                "horario_partida" => Carbon::now(),
                "id_global" => $row["id_global"] ? $row["id_global"] : null
            ];

            $dataAtual = Carbon::now();
            $dataAtual = $dataAtual->addHours(48);
            $leadExiste = Lead::where("nome_lead", $data["nome_lead"])
                ->where("ddd", $data["ddd"])
                ->where("telefone", $data["telefone"])
                ->where("email", $data["email"])
                ->where("extra", $data["extra"])
                ->where("plano_id", $plano_id)
                ->where("tipo_id", $tipo_id)
                ->where("idade", $data["idade"])
                ->where("cnpj", $data["cnpj"])
                ->whereDate("created_at", "<", $dataAtual)
                ->first();

            if(!empty($leadExiste)) {
                continue;
            }
            Lead::create($data);
        }
    }

    public function excluirLeadExterna($id_global)
    {
        $urls = ["https://mastersaudeleads.com.br/sistema/api/", "https://portalleads.com.br/app/api/", "https://www.indicasaude.com.br/app/api/"];
        foreach ($urls as $key => $url) {
            try {
                $client = new Client();

                // URL do servidor externo com o token secreto
                $secretToken = 'welltechparasempre';
                $url = $url.'lead/excluir/'. $id_global .'?token=' . $secretToken;
        
                $client->post($url);        
            } catch(Exception $e) {
                // tratar erro
            }
        }
    }
}
