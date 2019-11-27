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



    public function geInformationProfileAndtAccounts(array $whereParans=[], array $fields= [])
    {
        if (count($fields) == 0) {
            $fields= [
                'us.id','us.cpf','us.email','us.full_name','us.password','us.phone_number',
                'uscons.id_user as consumer_user_id','uscons.id as consumer_id','uscons.username as consumer_username',
                'seller.id_user as seller_user_id', 'seller.id as seller_id','seller.cnpj','seller.fantasy_name','seller.social_name',
                'seller.username as seller_username'
            ];
        }

        $resultSet = DB::table('user as us')
                                ->select($fields)
                                ->leftJoin('user_consumer as uscons','uscons.id_user', '=', 'us.id')
                                ->leftJoin('user_seller as seller','seller.id_user', '=', 'us.id');

        if (isset($whereParans['id_user'])) {
            $resultSet->where('us.id', $whereParans['id_user']);
        }

        if (isset($whereParans['username_general'])) {
            $resultSet->where('uscons.username', 'like',   $whereParans['username_general'] . "%");
            $resultSet->orWhere('seller.username', 'like',   $whereParans['username_general']. "%" );
            $resultSet->groupBy($fields);
        }

        $resultSet= $resultSet->get();

        if (count($resultSet) > 0) {
            return $resultSet;
        }
        return false;
    }
}
