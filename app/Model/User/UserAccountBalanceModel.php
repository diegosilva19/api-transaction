<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserAccountBalanceModel extends Model
{
    protected $table= 'user_account_balance';
    public $timestamps= false;
    protected $fillable = [
       'id_user', 'type_user_account', 'amount'
    ];

}
