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

    public function getAccounBalanceByIdSeller ($sellerId)
    {
        $resultSet = DB::table('user_seller as sel')
            ->select(['sel.id', 'balance.amount'])
            ->join('user as us','us.id', '=', 'sel.id_user')
            ->join('user_account_balance as balance','balance.id_user', '=', 'us.id')
            ->where('sel.id', '=', $sellerId)
            ->where('balance.type_user_account', 'seller')
            ->get();

        if (count($resultSet) == 0) {
            return false;
        }
        return $resultSet;
    }
}
