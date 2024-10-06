<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planos extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $fillable = [
        "ativo", "nome", "telefone", "email", "logo"
    ];
}
