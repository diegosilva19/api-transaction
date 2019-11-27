<?php


namespace App\Library\Validator\Request;

use App\Helpers\StringHelper;
use Illuminate\Contracts\Validation\ImplicitRule;

class PhoneNumberlRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        $phoneNumber=  StringHelper::normalizePhoNumber($value);
        $numberLength=  strlen($phoneNumber);

        if ($numberLength < 8  && $numberLength > 14) {
            return false;
        }
       return true;
    }

    public function message()
    {
        return ':attribute invalido!';
    }
}
