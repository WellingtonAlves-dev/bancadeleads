<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\Planos;
use App\Models\Tipos;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsFriasImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $telefone = $row["telefone"] ?? "";
            $ddd = 0;
            if ($telefone != "") {
                $telefone = strval(preg_replace("/[^0-9]/", "", $telefone));
                $ddd = substr($telefone, 0, 2);
                $telefone = substr($telefone, 2);
                $telefone = substr($telefone, 0, 5) . "-" . substr($telefone, 5);
            }

            // $preco = $row["preco"] ?? 0;
            $preco = 10.0;
            if ($preco != 0) {
                $preco = str_replace("R$", "", $preco);
            } else {
                $preco = 10;
            }

            $plano = Planos::where("nome", "LIKE", '%' . $row["plano"] . '%')->first();
            $plano_id = null;
            if ($plano) {
                $plano_id = $plano->id;
            } else {
                $plano_id = Planos::create([
                    "ativo" => true,
                    "nome" => strtoupper($row["plano"]),
                    "telefone" => "",
                    "email" => ""
                ])->id;
            }

            $tipo_id = null;
            $tipo = Tipos::where("nome", "LIKE", '%' . $row["tipo"] . '%')->first();
            if ($tipo) {
                $tipo_id = $tipo->id;
            } else {
                $tipo_id = Tipos::create([
                    "nome" => $row["tipo"]
                ])->id;
            }

            $data = [
                "ativo" => true,
                "preco" => $preco,
                "dias_disponivel" => 0,
                "qtd_vidas" => 1,
                "nome_lead" => strtoupper($row["nome"]) ?? "NÃO INFORMADO",
                "ddd" => $ddd,
                "telefone" => $telefone,
                "email" => $row["email"] ?? "NÃO INFORMADO",
                "extra" => $row["adicionais"] ?? "NÃO INFORMADO",
                "plano_id" => $plano_id,
                "tipo_id" => $tipo_id,
                "idade" => $row["idade"] ?? "NÃO INFORMADO",
                "cnpj" => in_array(strtolower($row["cnpj"] ?? ""), ["não", "nao", "n"]) == "Não" ? false : true,
                "horario_partida" => Carbon::now(),
            ];

            $dataAtual = Carbon::now();
            $dataAtual = $dataAtual->addHours(1000);
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
}
