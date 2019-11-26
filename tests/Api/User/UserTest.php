<?php


namespace Tests\Api\V1\User;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserCanBeInserted()
    {
        // Set
        $userDate =  [
                          "cpf"=> "35590959812",  "email"=> "diego.silva_18@yahoo.com.br",
                           "full_name"=>"Diego Tavares da Silva", "password"=> "teste" ,
                           "phone_number"=> "11986542491"
                     ];

        // Actions
        $this->post("/users", $userDate);

        $jsonReponse = json_encode($this->response->content());

        // Assertions
        $this->assertEquals(200, $this->getStatusCode());

        $this->assertExactJson(
            [
                'cpf' => '35590959812',
                'email' => 'email',
                'full_name' => 'Diego Tavares da Silva',
                'password' => 'teste',
                'email' => 'diego.silva_18@yahoo.com.br'
            ]
        );
    }
}