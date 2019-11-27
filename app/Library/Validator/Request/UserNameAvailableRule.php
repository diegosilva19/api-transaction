<?php


namespace App\Library\Validator\Request;

use App\Model\User\UserModel;
use Illuminate\Contracts\Validation\ImplicitRule;

class UserNameAvailableRule implements ImplicitRule
{

    public function passes($attribute, $value)
    {
        $userModel = new UserModel();
        $available = $userModel->checkUserNameIsAvailable($value);
        if ($available) {
            return true;
        }
        return false;
    }

    public function message()
    {
        return ':attribute já está cadastrado utilize outro !';
    }
}