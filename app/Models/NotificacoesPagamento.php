<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacoesPagamento extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $fillable = [
        "user_id","preference_id",
        "topic","id_payment",
        "status", "amount",
        "unique_key"
    ];
}
