<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\MinhasLeads;
use App\Models\Planos;
use App\Models\Saldos;
use App\Models\Tipos;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;

class Importadores extends Controller
{
    public function importarCsv(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|mimes:csv,txt',
            "tabela" => "required"
        ]);

        $arquivo = $request->file('arquivo');
        $caminho = $arquivo->getRealPath();
        $dados = array_map('str_getcsv', file($caminho));

        // Removendo cabeçalho
        $cabecalho = array_shift($dados);
        $logs = [];
        if($request->tabela == "users") {
            $this->importarUsers($dados);
        } elseif ($request->tabela == "leads") {
            $logs = $this->importarLeads($dados);
        } else {
            throw new Exception("Selecione a tabela correta");
        }

        return back()->with(['success' => 'Importação concluída!', "logs" => $logs]);
    }

    public function importarLeads($dados) {
        $errors = [];
        foreach($dados as $linha ) {
            try {


                $usuario_lead = intval($linha[13] ?? 0);
                if($usuario_lead == 0 || $usuario_lead == false || $usuario_lead == null) {
                    continue;
                }
                $id = intval($linha[0]);
                $ativo = true;
                $horario_partida = Carbon::now();
                $dias_disponivel = 10;
                $qtd_vidas = preg_replace('/\D/', '', $linha[7]); 
                $qtd_vidas = !empty($qtd_vidas) ? (int)$qtd_vidas : 1;                
                $nome_lead = $linha[2];
                $ddd = $linha[5];
                $telefone = $linha[4];
                $email = $linha[3];
                $extra = $linha[9];
                $plano = $linha[8];
                $tipo = $linha[6];
                if($tipo == "" || $tipo == null || $tipo == "NULL") {
                    $tipo = "Migração tipo";
                }
                if($plano == "" || $plano == null || $plano == "NULL") {
                    $plano = "Migração plano";
                }
                if($ddd == "" || $ddd == null || $ddd == "NULL") {
                    $ddd = "11";
                }
                if($telefone == "" || $telefone == null || $telefone == "NULL") {
                    $telefone = "99999999999";
                }
                if($email == "" || $email == null || $email == "NULL") {
                    $email = "email@email.com";
                }
                $tipo_id = 1;
                $plano_id = 1;
                $cnpj = false;
                $idade = $qtd_vidas;
                $preco = $linha[10];
                // Procurar se o plano existe

                $planoExistente = Planos::where("nome", $plano)->first();
                if($planoExistente) {
                    $plano_id = $planoExistente->id;
                } else {
                    $nPlano = Planos::create([
                        "ativo" => true,
                        "nome" => $plano,
                        "telefone" => "11999999999",
                        "email" => "email@email.com"
                    ]);
                    $plano_id = $nPlano->id;
                }

                $tipoExistente = Tipos::where("nome", $tipo)->first();
                if($tipoExistente) {
                    $tipo_id = $tipoExistente->id;
                } else {
                    $nTipo = Tipos::create([
                        "nome" => $tipo,
                        "preco_fria" => 10.0,
                    ]);
                    $tipo_id = $nTipo->id;
                }

                if($tipo == "Empresarial") {
                    $cnpj = true;
                }

                // Verificar se a lead existe
                if(Lead::where("id", $id)->first()) {
                    $errors[] = "Lead $id já existe";
                    continue;
                }

                $lead = new Lead(); 

                $lead->id = $id;
                $lead->ativo = true;
                $lead->preco = floatval($preco);
                $lead->horario_partida = Carbon::now();
                $lead->dias_disponivel = 1;
                $lead->qtd_vidas = $qtd_vidas;
                $lead->nome_lead = $nome_lead;
                $lead->ddd = $ddd;
                $lead->telefone = $telefone;
                $lead->email = $email;
                $lead->extra = $extra;
                $lead->plano_id = $plano_id;
                $lead->tipo_id = $tipo_id;
                $lead->cnpj = $cnpj;
                $lead->idade = $idade;
                $lead->id_global = null;
                $lead->origem = "importador";
                $lead->save();

                $minhaLead = new MinhasLeads();
                $minhaLead->id_lead = $lead->id;
                $minhaLead->id_user = $usuario_lead;
                $minhaLead->save();

            } catch(\Exception $e) {
                $errors[] = $e->getMessage();
                // print_r($e->getMessage());
            }
        }

        return $errors;
    }

    public function importarUsers($dados) {
        foreach($dados as $linha) {
            DB::beginTransaction();
            try {
                $id_usuario = intval($linha[0]);
                $nome_usuario = $linha[1];
                $email = $linha[2];
                $telefone = $linha[3];
                $senha = $linha[4];
                $ativo = $linha[5];
                $saldo = floatval($linha[7]);    
                // Criar o usuário
                $user = new User();
                $user->id = $id_usuario;
                $user->name = $nome_usuario;
                $user->email = $email;
                $user->telefone = $telefone;
                $user->password = $senha;
                $user->ativo = $ativo == 1 ? true : false;
                $user->email_verified_at = Carbon::now();
                $user->save();
                // INSERIR SALDO DO USUÁRIO
                $Saldos = new Saldos();
                $Saldos->gerarEntrada($user->id, $saldo, "MIGRAÇÃO DO SISTEMA", true, 0.0);
                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
            }
        }   
    }

}
