<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reposicao extends Model
{
    use HasFactory;
    protected $fillable = [
        "solicitante",
        "lead_id",
        "descricao",
        "status",
        "motivo_reprovacao"
    ];
}
