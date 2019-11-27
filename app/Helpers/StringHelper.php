<?php


namespace App\Helpers;


class StringHelper
{

    public static function normalizePhoNumber(string $phoneNumber) : string
    {
        $replace = ['(', ')', '+', '-'];
        $phoneNumber= str_replace($replace, '', $phoneNumber);
        return $phoneNumber;
    }

    public static function normalizeCnpjCpf(string $phoneNumber) : string
    {
        $replace = ['/', '.', '-'];
        $phoneNumber= str_replace($replace, '', $phoneNumber);
        return $phoneNumber;
    }

    public static function checkHaveComppleNameInString(string $name) : bool
    {
        return count(explode(' ', $name)) > 1 ? true : false ;
    }
}
