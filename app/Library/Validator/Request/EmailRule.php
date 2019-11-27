<?php


namespace App\Library\Validator\Request;

use Illuminate\Contracts\Validation\ImplicitRule;

class EmailRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        if (preg_match('/[a-z0-9._-]+@[a-z0-9_]+\.[a-z0-9._]+/im', $value) == 0) {
            return false;
        }
       return true;
    }

    public function message()
    {
        return ':attribute invalido!';
    }
}
