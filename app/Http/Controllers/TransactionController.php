<?php

namespace App\Http\Controllers;

use Alphametric\Validation\Rules\DecimalRule;
use App\Library\ModelRequestFactory;
use App\Library\Validator\Request\CnpjRule;
use App\Library\Validator\Request\CpftRule;

use App\Library\Validator\Request\HttpStatusApi;
use App\Library\Validator\Request\UserNameAvailableRule;
use App\Library\Validator\Request\ValidatorRequest;
use App\Model\User\TransactionModel;
use App\Model\User\UserAccountBalanceModel;
use App\Model\User\UserConsumerModel;
use App\Model\User\UserModel;
use App\Model\User\UserSellerModel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(Request $request)
    {
        $response=['json'=>
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>''], 'status'=> HttpStatusApi::SUCCESS];

        $validatorRequest= new ValidatorRequest($request,  [
            'payee_id'=> ['required', 'max:60'], //seller
            'payer_id'=> ['required', 'max:60'],//consuer
            'value'=> ['required', 'max:25' ]
        ]);


        if(!$validatorRequest->validate()) {
            $response['status']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['code']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['message']= "Erros Encontrados: " .$validatorRequest->getErrosPrintable(" | ");
            return response()->json($response['json'], $response['status']);
        }

        $userConsumerModel = new UserConsumerModel();
        $userSellerModel = new UserSellerModel();
        $transactionModel = new TransactionModel();
        $amountTransaction=  str_replace(',', '', $request->toArray()['value']);
        $amountTransaction=  sprintf('%.2f',  $amountTransaction);

        $consumerTransaction = $userConsumerModel->getAccounBalanceByIdConsumer($request->toArray()['payer_id']);
        $sellerTransaction = $userSellerModel->getAccounBalanceByIdSeller($request->toArray()['payee_id']);

        $errorUser=[];
        if (!$sellerTransaction) {
            $errorUser['payee_id']='payee_id (vendedor) não encontrado';
        }

        if (!$consumerTransaction) {
            $errorUser['payer_id']='payer_id (consumidor) não encontrado';
        }

        if (count($errorUser) > 0) {
            $response['status']= HttpStatusApi::USER_NOT_FOUND;
            $response['json']['code']= HttpStatusApi::USER_NOT_FOUND;
            $response['json']['message']= implode("  |  ", $errorUser);
            return response()->json($response['json'], $response['status']);
        }

        if ($amountTransaction > 100) {
            $response['status']= HttpStatusApi::TRANSACTION_NOT_AUTHORIZED;
            $response['json']['code']= HttpStatusApi::TRANSACTION_NOT_AUTHORIZED;
            $response['json']['message']= "Transação não autoriazada valor {$amountTransaction} superior 100.00 !";
        } else {
            try{
                $transactionModel->payee_id= $sellerTransaction[0]->id;
                $transactionModel->payer_id= $consumerTransaction[0]->id;
                $transactionModel->amount= $amountTransaction;
                $transactionModel->save();

                $transactionData = $transactionModel->getById($transactionModel->id);
                $transactionData= $transactionData[0];
                unset($transactionData->authorization_code);

                $response['status']= HttpStatusApi::SUCCESS_CREATED;
                $response['json']=$transactionData;

            } catch (Exception $ex) {
                $response['status']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                $response['json']['code']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                $response['json']['message']= $ex->getMessage();

                Log::error("Apicacao com erro requisição banco de dados ");
                Log::error($ex->getMessage());
            }
        }

        return response()->json($response['json'], $response['status']);
    }

    public function get(Request $request, string $transactionId)
    {
        $response=['json'=>
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>''], 'status'=> HttpStatusApi::SUCCESS];

        try {
            $transactionModel =new TransactionModel();
            $transaction= $transactionModel->getById($transactionId);

            if ($transaction) {
                $transaction= $transaction[0];
                $response['json']= $transaction;
            } else {
                $response['json']['message']=  'Transação não encontrada';
                $response['json']['code']=  HttpStatusApi::USER_NOT_FOUND;
                $response['status'] = HttpStatusApi::USER_NOT_FOUND;
            }
        } catch(Exception $ex){
            $response['json']['message']=  'Erro Interno do Servidor';
            $response['json']['code']=  HttpStatusApi::INTERNAL_SERVER_ERROR;
            $response['status'] = HttpStatusApi::USER_NOT_FOUND;
            Log::error("Apicacao com erro requisição banco de dados ");
            Log::error($ex->getMessage());
            return response()->json($response['json'],$response['status']);
        }

        return response()->json($response['json'],$response['status']);
    }
}
