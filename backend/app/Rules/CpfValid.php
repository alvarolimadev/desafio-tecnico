<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CpfValid implements Rule
{
    public function passes($attribute, $value): bool
    {
        // Remove tudo que não é número
        $cpf = preg_replace('/\D/', '', $value);

        // Deve ter 11 dígitos
        if (strlen($cpf) !== 11) {
            return false;
        }

        // Não pode ser sequência repetida
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Validação dos dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += $cpf[$i] * (($t + 1) - $i);
            }

            $digit = ((10 * $sum) % 11) % 10;

            if ($cpf[$i] != $digit) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'O CPF informado é inválido.';
    }
}
