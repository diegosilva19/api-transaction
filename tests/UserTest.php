<?php
use Laravel\Lumen\Testing\DatabaseTransactions;


class UserTest extends TestCase
{
    //use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

    public function testUserCanBeInserted()
    {
        // Set
        $userDate =  [
                          "cpf"=> "575.109.000-42",  "email"=> "email@phpunit.com.br",
                           "full_name"=>"Diego Tavares", "password"=> "teste" ,
                           "phone_number"=> "11986542491"
                     ];
        $userDate=['q'=> 'emp'];
        // Actions
        $this->get("/users", $userDate);

        $jsonReponse = $this->response->getContent();

        // Assertions
        $this->assertEquals(200, $this->response->getStatusCode());

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
