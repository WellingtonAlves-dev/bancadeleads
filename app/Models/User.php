<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ativo',
        'name',
        'email',
        'password',
        "email",
        "telefone",
        "role",
        "vinculado",
        "user_master",
        'observacao',
        "email_verified_at"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getValorSaldo($formatted = false) {
        $user_id = $this->id;
        $valor = 0;
        $saldos = Saldos::where("user_id", $user_id)->get();
        foreach($saldos as $saldo) {
            if($saldo->tipo == "entrada") {
                $valor += $saldo->valor;
            } else {
                $valor -= $saldo->valor;
            }
        }
        if($formatted) {
            return number_format($valor, 2, ",", ".");
        }
        return $valor;
    }
    public function saldos()
    {
        return $this->hasMany(Saldos::class, 'user_id');
    }
}
