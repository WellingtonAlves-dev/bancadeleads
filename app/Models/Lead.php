<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $fillable = [
        "ativo",
        "preco",
        "horario_partida",
        "dias_disponivel",
        "qtd_vidas",
        "nome_lead",
        "ddd",
        "telefone",
        "email",
        "extra",
        "plano_id",
        "tipo_id",
        "cnpj",
        "idade",
        "id_global",
        "origem"
    ];

    public function tipo() {
        return $this->hasOne(Tipos::class, "id", "tipo_id");
    }
    public function plano() {
        return $this->hasOne(Planos::class, "id", "plano_id");
    }
}
