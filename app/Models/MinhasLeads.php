<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinhasLeads extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $fillable = [
        "id_lead",
        "id_user",
        "observacao"
    ];

    public function leadInfo() {
        return $this->hasOne(Lead::class, "id", "id_lead");
    }
}
