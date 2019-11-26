<?php


namespace App\Library\Validator\Request;

use Illuminate\Contracts\Validation\ImplicitRule;

class CnpjRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        return strlen($value) == 12;
    }

    public function message()
    {
        return ':attribute invalido!';
    }
}