<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldos extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $fillable = [
        "user_id","tipo", "valor", "observação", "lead_id", "saldo_reposicao", "lead_fria", "saldo_atual", "saldo_anterior"
    ];
    protected $dates = ['created_at'];

    public function gerarEntrada($user_id, $valor, $observacao, $ignorar_saldo_reposicao = false) {

        $user = User::where("id", $user_id)->first();
        $saldoUser = $user->getValorSaldo();
        $this->saldo_atual = $saldoUser + $valor;
        $this->saldo_anterior = $saldoUser;

        $valor_saldo_reposicao = ($valor * 30) / 100;
        $this->tipo = "entrada";
        $this->user_id = $user_id;
        $this->valor = $valor;
        $this->observação = $observacao;
        if(!$ignorar_saldo_reposicao) {
            $this->saldo_reposicao = $valor_saldo_reposicao;
        }
        $this->save();
        if(!$ignorar_saldo_reposicao) {
            $saldoReposicaoAtual = $this->saldoReposicaoAnterior($user_id, $this->id);
            if($saldoReposicaoAtual === null) {
                $saldoReposicaoAtual = 0;
            }
            $saldoReposicaoAtual += $valor_saldo_reposicao;
            $this->atualizarSaldoReposicaoByUser($user_id, $saldoReposicaoAtual);    
        }

    }
    public function gerarSaida($user_id, $valor, $observacao, $lead_id = null, $lead_fria = false) {
        $user = User::where("id", $user_id)->first();
        $saldoUser = $user->getValorSaldo();
        $this->saldo_atual = $saldoUser - $valor;
        $this->saldo_anterior = $saldoUser;

        $this->tipo = "saida";
        $this->user_id = $user_id;
        $this->valor = $valor;
        $this->observação = $observacao;
        $this->lead_id = $lead_id;
        $this->lead_fria = $lead_fria;
        $this->save();
    }
    public static function atualizarSaldoReposicaoByUser($user_id, $valor) {
        $saldo_reposicao = Saldos::where("tipo", "entrada")
            ->where("user_id", $user_id)
            ->orderBy("id", "desc")
            ->first();
        if(empty($saldo_reposicao)) {
            throw new Exception("O usuário precisa ter preenchido ao menos um saldo");
        }
        $saldo_reposicao->saldo_reposicao = $valor;
        $saldo_reposicao->save();
    }
    public static function saldoReposicaoByUser($user_id) : float | null {
        $valor_saldo_reposicao = null;
        $saldo_reposicao = Saldos::where("tipo", "entrada")
            ->where("user_id", $user_id)
            ->orderBy("id", "desc")
            ->first();

        if(!empty($saldo_reposicao)) {
            if($saldo_reposicao->saldo_reposicao === null) {
                $valor_saldo_reposicao = ( $saldo_reposicao->valor * 30 ) / 100;
                $saldo_reposicao->saldo_reposicao = $valor_saldo_reposicao;
                $saldo_reposicao->save();
            } else {
                $valor_saldo_reposicao = $saldo_reposicao->saldo_reposicao;
            }
        }

        return $valor_saldo_reposicao;
    }
    public static function saldoReposicaoAnterior($user_id, $idSaldoForIgnore) {
        $saldo = Saldos::where("tipo", "entrada")
            ->where("user_id", $user_id)
            ->where("id", "!=",$idSaldoForIgnore)
            ->orderBy("id", "desc")
            ->first();
        return $saldo->saldo_reposicao ?? 0;
    }

    public function lead() {
        return $this->hasOne(Lead::class, "id", "lead_id");
    }
    public function user() {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
