<?php


namespace App\Library\Validator\Request;

use App\Helpers\StringHelper;
use Illuminate\Contracts\Validation\ImplicitRule;

class CompleteNameRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        return StringHelper::checkHaveComppleNameInString($value);
    }

    public function message()
    {
        return ':attribute : deve conter o nome completo!';
    }
}
