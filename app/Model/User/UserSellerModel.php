<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserSellerModel extends Model
{
    protected $table= 'user_seller';
    public $timestamps = false;
    protected $fillable = [
       'cnpj', 'fantasy_name', 'social_name', 'username', 'id_user'
    ];


    public function checkAlreadySellerById($userId)
    {
        $resultSet = DB::table('user as us')
            ->select('us.id')
            ->join('user_seller as seller','seller.id_user', '=', 'us.id')
            ->where('us.id', $userId)
            ->get();

        if (count($resultSet) == 0) {
            return false;
        }
        return true;
    }
}
