<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UserModel extends Model
{
    protected $table= 'user';
    public $timestamps = false;
    protected $fillable = [
        'cpf', 'email', 'full_name',  'password', 'phone_number'
    ];



    public function checkIsUniqueUser(string $email, string $cpf)
    {
        $table = DB::table('user')->select('id', 'cpf', 'email', 'full_name',  'password', 'phone_number');
        $table->where('email', $email)->orWhere('cpf', $cpf);
        $resultSet = $table->get();

         if (count($resultSet) > 0) {
             return true;
         }
         return false;
    }

    public function getUserById(string $id)
    {
        $resultSet = DB::table('user')->where('id', $id)->get();
        if (count($resultSet) == 0) {
            return false;
        }
        return $resultSet;
    }
}
