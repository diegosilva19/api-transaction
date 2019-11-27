<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Library\ModelRequestFactory;
use App\Library\Validator\Request\CnpjRule;
use App\Library\Validator\Request\CpftRule;

use App\Library\Validator\Request\HttpStatusApi;
use App\Library\Validator\Request\UserNameAvailableRule;
use App\Library\Validator\Request\ValidatorRequest;
use App\Model\User\UserAccountBalanceModel;
use App\Model\User\UserConsumerModel;
use App\Model\User\UserModel;
use App\Model\User\UserSellerModel;
use Illuminate\Http\Request;
use Exception;

class UserSellerController extends Controller
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
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>'Usuario já cadastrado'], 'status'=> HttpStatusApi::SUCCESS];

        $validatorRequest= new ValidatorRequest($request,  [
            'cnpj'=> ['required', new CnpjRule],
            'fantasy_name'=> ['required', 'max:60'],
            'social_name'=> ['required', 'max:60'],
            'user_id'=> ['required', 'max:25'],
            'username'=> ['required', 'max:50', new UserNameAvailableRule],
        ]);


        if(!$validatorRequest->validate()) {

            $response['status']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['code']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['message']= "Erros Encontrados: " .$validatorRequest->getErrosPrintable(" | ");
            return response()->json($response['json'], $response['status']);
        }


        $idUser= $request->toArray()['user_id'];
        $userNameConsumer = $request->toArray()['username'];
        $sellerModel = ModelRequestFactory::factory(UserSellerModel::class, $request);
        $userModel = new UserModel();

        $foundedUser = $userModel->getUserById($idUser);

        if ($foundedUser) {
            $checkRelationShip = $sellerModel->checkAlreadySellerById($foundedUser[0]->id);

            if (!$checkRelationShip) {
                try {
                    $sellerModel->cnpj= StringHelper::normalizeCnpjCpf($sellerModel->cnpj);
                    $sellerModel->id_user=  $foundedUser[0]->id;
                    $sellerModel->save();

                    $response['status']=HttpStatusApi::SUCCESS_CREATED;

                    $response['json']=  $sellerModel->toArray();
                    $response['json']['user_id']= $response['json']['id_user'];
                    unset($response['json']['id_user']);

                    $accountBalance = new UserAccountBalanceModel();
                    $accountBalance->id_user = $foundedUser[0]->id;
                    $accountBalance->type_user_account = 'seller';
                    $accountBalance->save();
                } catch(Exception $ex) {
                    $response['status']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['code']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['message']= 'Erro ao inserir no banco de dados';
                    Log::error("Apicacao com erro requisição banco de dados ");
                    Log::error($ex->getMessage());
                }
            } else {
                $response['json']['message']= "Usuario já cadastrado como seller";
            }
        } else {
            $response['status']= HttpStatusApi::USER_NOT_FOUND;
            $response['json']['code']= HttpStatusApi::USER_NOT_FOUND;
            $response['json']['message']= "Usuario inválido";
        }

        return response()->json($response['json'], $response['status']);
    }
}
