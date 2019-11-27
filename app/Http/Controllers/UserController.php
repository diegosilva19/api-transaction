<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Library\ModelRequestFactory;
use App\Library\Validator\Request\CnpjRule;
use App\Library\Validator\Request\CompleteNameRule;
use App\Library\Validator\Request\CpftRule;

use App\Library\Validator\Request\EmailRule;
use App\Library\Validator\Request\HttpStatusApi;
use App\Library\Validator\Request\PhoneNumberlRule;
use App\Library\Validator\Request\ValidatorRequest;
use App\Model\User\UserAccountBalanceModel;
use App\Model\User\UserConsumerModel;
use App\Model\User\UserModel;
use Faker\Provider\PhoneNumber;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;


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

        $queryStringSearchUsername= $request->get('q');

        $response=['json'=>
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>'Usuario já cadastrado'], 'status'=> HttpStatusApi::SUCCESS];

        try {
            $userModel =new UserModel();
            $searchParans= ['username_general'=> $queryStringSearchUsername];
            $fields= ['us.cpf', 'us.email', 'us.full_name', 'us.id', 'us.password', 'us.phone_number'];

            $userAccounts= $userModel->geInformationProfileAndtAccounts($searchParans, $fields);

            if ($userAccounts) {
                $response['json']=$userAccounts;
                $response['status'] = HttpStatusApi::SUCCESS;
            } else {
                $response['json']['message']=  'Usuário não encontrado';
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

    public function getById(Request $request, string $id_user)
    {
        $response=['json'=>
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>'Usuario já cadastrado'], 'status'=> HttpStatusApi::SUCCESS];

        try {
            $userModel =new UserModel();
            $searchParans= ['id_user'=> $id_user];
            $userAccounts= $userModel->geInformationProfileAndtAccounts($searchParans);

            if ($userAccounts) {
                $userAccounts= $userAccounts[0];

                $accountJson= [];
                if (is_numeric($userAccounts->consumer_id)) {
                    $accountJson['consumer']['id'] = $userAccounts->consumer_id;
                    $accountJson['consumer']['user_id'] = $id_user;
                    $accountJson['consumer']['username'] = $userAccounts->consumer_username;
                }

                if (is_numeric($userAccounts->seller_id)) {
                    $accountJson['seller']['cnpj'] = $userAccounts->cnpj;
                    $accountJson['seller']['fantasy_name'] = $userAccounts->fantasy_name;
                    $accountJson['seller']['id'] = $userAccounts->seller_id;
                    $accountJson['seller']['social_name'] = $userAccounts->social_name;
                    $accountJson['seller']['user_id'] = $id_user;
                    $accountJson['seller']['username'] = $userAccounts->seller_username;
                }

                $userInformation= ['accounts'=> $accountJson, 'user'=>[]];
                $userInformation['user']['cpf']=$userAccounts->cpf;
                $userInformation['user']['email']=$userAccounts->email;
                $userInformation['user']['full_name']=$userAccounts->full_name;
                $userInformation['user']['id']=$id_user;
                $userInformation['user']['password']=$userAccounts->password;
                $userInformation['user']['phone_number']=$userAccounts->phone_number;

                $response['json'] = $userInformation;
            } else {
                $response['json']['message']=  'Usuário não encontrado';
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

    public function store(Request $request)
    {
        $response=['json'=>
            ['code'=> HttpStatusApi::SUCCESS, 'message'=>'Usuario já cadastrado'], 'status'=> HttpStatusApi::SUCCESS];

        $validatorRequest= new ValidatorRequest($request,  [
            'cpf'=> ['required', new CpftRule],
            'email'=> ['required','max:100', new EmailRule] ,
            'full_name'=> ['required','max:100', new CompleteNameRule],
            'password'=> ['required','max:25'],
            'phone_number'=> ['required', new PhoneNumberlRule]
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
                    $userModel->cpf = StringHelper::normalizeCnpjCpf($userModel->cpf);
                    $userModel->phone_number = StringHelper::normalizePhoNumber($userModel->phone_number);
                    $userModel->save();
                    $response['json']= $userModel->jsonSerialize();
                    $response['status']=HttpStatusApi::SUCCESS_CREATED;
                } catch (\PDOException $ex) {
                    $response['status']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['code']= HttpStatusApi::INTERNAL_SERVER_ERROR;
                    $response['json']['message']= 'Erro ao inserir no banco de dados';

                    Log::error("Apicacao com erro requisição banco de dados ");
                    Log::error($ex->getMessage());
                }
            } else {
                $response['json']['message']= "Usuario já cadastrado";
            }
        }
        return response()->json($response['json'], $response['status']);
    }
}
