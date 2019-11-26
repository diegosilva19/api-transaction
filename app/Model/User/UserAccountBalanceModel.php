<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserAccountBalanceModel extends Model
{
    protected $table= 'users';
    protected $fillable = [
       'id_user', 'type_user_account', 'amount'
    ];

    protected $dates=false;

}
