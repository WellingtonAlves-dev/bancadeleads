<?php

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher;

class Sha1Hasher implements Hasher
{
    /**
     * Gera o hash da senha (SHA-1).
     */
    public function make($value, array $options = [])
    {
        return sha1($value);
    }

    /**
     * Verifica se a senha informada corresponde ao hash armazenado.
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return sha1($value) === $hashedValue;
    }

    /**
     * SHA-1 não precisa ser rehash, mas aqui podemos validar se precisa atualizar para bcrypt.
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }

    /**
     * Retorna informações sobre o hash (requerido pelas versões mais novas do Laravel).
     */
    public function info($hashedValue)
    {
        return [
            'algo' => PASSWORD_DEFAULT, // Apenas um valor padrão para compatibilidade
            'algoName' => 'sha1',
            'options' => [],
        ];
    }
}
