<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserConsumerModel extends Model
{
    protected $table= 'user_consumer';
    public $timestamps = false;
    protected $fillable = [
       'id_user', 'username'
    ];

    public function checkExistsUser()
    {

    }

    public function checkAlreadyConsumerById(int $userId) : bool
    {
        $resultSet = DB::table('user as us')
                        ->select('us.id')
                        ->join('user_consumer as uscons','uscons.id_user', '=', 'us.id')
                        ->where('us.id', $userId)
                        ->get();

        if (count($resultSet) == 0) {
            return false;
        }
        return true;
    }

    public function getAccounBalanceByIdConsumer ($consumerId)
    {

        $resultSet = DB::table('user_consumer as cons')
            ->select(['cons.id', 'balance.amount'])
            ->join('user as us','us.id', '=', 'cons.id_user')
            ->join('user_account_balance as balance','balance.id_user', '=', 'us.id')
            ->where('cons.id', $consumerId)
            ->where('balance.type_user_account', 'consumer')
            ->get();

        if (count($resultSet) == 0) {
            return false;
        }
        return $resultSet;
    }
}
