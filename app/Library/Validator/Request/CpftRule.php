<?php


namespace App\Library\Validator\Request;

use Illuminate\Contracts\Validation\ImplicitRule;

class CpftRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        $validCpf= true;
        if (strlen($value) != 11) {
            $validCpf= false;
        }

        return $validCpf;
    }

    public function message()
    {
        return ':attribute invalido!';
    }
}