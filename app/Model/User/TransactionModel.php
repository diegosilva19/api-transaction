<?php


namespace App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

class TransactionModel extends Model
{
    protected $table= 'transaction';
    public $timestamps = false;
    protected $fillable = [
       'payee_id', 'payer_id', 'amount', 'authorization_code', 'date_transaction'
    ];


    public function getById(int $idTransaction)
    {
        $resultSet = DB::table('transaction')
            ->select(['amount', 'payee_id', 'payer_id', 'authorization_code', 'date_transaction as transaction_date' ] )
            ->where('id', $idTransaction)
            ->get();

        if (count($resultSet) == 0) {
            return false;
        }
        return $resultSet;
    }
}
