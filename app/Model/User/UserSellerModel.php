<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserSellerModel extends Model
{
    protected $table= 'users';
    protected $fillable = [
       'cnpj', 'fantasy_name', 'social_name', 'username', 'id_user'
    ];

    protected $dates=false;

}
