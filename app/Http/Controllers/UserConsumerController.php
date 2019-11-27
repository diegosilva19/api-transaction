<?php

namespace App\Http\Controllers;

use App\Library\Validator\Request\HttpStatusApi;
use App\Library\Validator\Request\ValidatorRequest;
use App\Model\User\UserAccountBalanceModel;
use App\Model\User\UserConsumerModel;
use App\Model\User\UserModel;
use Illuminate\Http\Request;

class UserConsumerController extends Controller
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
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>'Consumidor já cadastrado'], 'status'=> HttpStatusApi::SUCCESS];

        $validatorRequest= new ValidatorRequest($request,  [
            'user_id'=> ['required'],
            'username'=> ['required', 'max:50'],
        ]);

        if(!$validatorRequest->validate()) {

            $response['status']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['code']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['message']= "Erros Encontrados: " .$validatorRequest->getErrosPrintable(" | ");
            return response()->json($response['json'], $response['status']);
        }

        $idUser= $request->toArray()['user_id'];
        $userNameConsumer = $request->toArray()['username'];
        $consumerModel = new UserConsumerModel();
        $userModel = new UserModel();

        $foundedUser = $userModel->getUserById($idUser);

        if ($foundedUser) {
            $checkRelationShip = $consumerModel->checkAlreadyConsumerById($foundedUser[0]->id);

            if (!$checkRelationShip) {
                try {
                    $consumerModel->id_user=  $foundedUser[0]->id;
                    $consumerModel->username= $userNameConsumer;

                    $consumerModel->save();

                    $response['status']=HttpStatusApi::SUCCESS_CREATED;
                    $response['json']= [
                        'id'=> $consumerModel->id,
                        'user_id'=> $foundedUser[0]->id,
                        'username'=>$userNameConsumer
                    ];

                    $accountBalance = new UserAccountBalanceModel();
                    $accountBalance->id_user = $foundedUser[0]->id;
                    $accountBalance->type_user_account = 'consumer';
                    $accountBalance->save();
                } catch(Exception $ex) {
                    $response['status']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['code']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['message']= "Erro ao inserir no banco de dados";

                    Log::error("Apicacao com erro requisição banco de dados ");
                    Log::error($ex->getMessage());
                }
            } else {
                $response['json']['message']= "Usuario já cadastrado como consumer";
            }
        } else {
            $response['status']= HttpStatusApi::USER_NOT_FOUND;
            $response['json']['code']= HttpStatusApi::USER_NOT_FOUND;
            $response['json']['message']= "Usuario inválido";
        }

        return response()->json($response['json'], $response['status']);
    }
}
