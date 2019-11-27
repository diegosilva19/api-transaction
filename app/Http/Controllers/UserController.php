<?php

namespace App\Http\Controllers;

use App\Library\ModelRequestFactory;
use App\Library\Validator\Request\CnpjRule;
use App\Library\Validator\Request\CpftRule;

use App\Library\Validator\Request\HttpStatusApi;
use App\Library\Validator\Request\ValidatorRequest;
use App\Model\User\UserAccountBalanceModel;
use App\Model\User\UserConsumerModel;
use App\Model\User\UserModel;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
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

    public function get(Request $request)
    {
        return response()->json(['queryString'=> $request->get('q')],200);
    }

    public function getById(Request $request, string $userId)
    {
        return response()->json(['status'=>$userId],200);
    }

    public function store(Request $request)
    {
        $response=['json'=>
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>'Usuario já cadastrado'], 'status'=> HttpStatusApi::SUCCESS];

        $validatorRequest= new ValidatorRequest($request,  [
            'cpf'=> ['required', new CpftRule],
            'email'=> 'required|max:100',
            'full_name'=> 'required|max:100',
            'password'=> 'required|max:25',
            'phone_number'=> 'required|max:14'
        ]);

        if(!$validatorRequest->validate()) {

            $response['status']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['code']= HttpStatusApi::VALIDATION_ERROR;
            $response['json']['message']= "Erros Encontrados: " .$validatorRequest->getErrosPrintable(" | ");

        } else {

            $userModel = ModelRequestFactory::factory(UserModel::class, $request);
            $foundedUser = $userModel->checkIsUniqueUser($userModel->email, $userModel->cpf);

            if (!$foundedUser) {
                try {
                    $userModel->save();
                    $response['json']= $userModel->jsonSerialize();
                    $response['status']=HttpStatusApi::SUCCESS_CREATED;
                } catch (\PDOException $e) {
                    $response['status']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['code']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['message']= 'Erro ao inserir no banco de dados';
                }
            } else {
                $response['json']['message']= "Usuario já cadastrado";
            }
        }
        return response()->json($response['json'], $response['status']);
    }
}
