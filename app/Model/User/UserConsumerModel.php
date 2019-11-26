<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserConsumerModel extends Model
{
    protected $table= 'users';
    protected $fillable = [
       'id_user', 'username'
    ];

    protected $dates=false;

}
