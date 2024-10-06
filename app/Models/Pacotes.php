<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacotes extends Model
{
    use HasFactory;
    protected $fillable = ["ativo", "name", "qtd_leads", "qtd_desconto", "descricao"];
}
