<?php


namespace App\Library\Validator\Request;

use App\Helpers\StringHelper;
use Illuminate\Contracts\Validation\ImplicitRule;

class CpftRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        $cpfNumber = StringHelper::normalizeCnpjCpf($value);

        if (strlen($cpfNumber) != 11) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return ':attribute invalido!';
    }
}
