<?php


namespace App\Library\Validator\Request;

use App\Helpers\StringHelper;
use Illuminate\Contracts\Validation\ImplicitRule;

class CnpjRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        $cnpj= StringHelper::normalizeCnpjCpf($value);

        if (strlen($cnpj) != 14) {
            return  false;
        }
        return true;
    }

    public function message()
    {
        return ':attribute invalido!';
    }
}
