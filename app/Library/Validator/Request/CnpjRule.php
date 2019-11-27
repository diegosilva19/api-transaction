<?php


namespace App\Library\Validator\Request;

use Illuminate\Contracts\Validation\ImplicitRule;

class CnpjRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        $validCnpj= true;
        if (strlen($value) != 14) {
            $validCnpj= false;
        }


        return $validCnpj;
    }

    public function message()
    {
        return ':attribute invalido!';
    }
}