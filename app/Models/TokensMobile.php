<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokensMobile extends Model
{
    use HasFactory;
    protected $fillable = ["token"];
    public $timestamps = false;
}
