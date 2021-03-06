<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix'=> 'users',
], function() use ($router) {
    $router->get('/', 'UserController@get'); //return base na query string
    $router->post('/', 'UserController@store');// insert user
    $router->post('/consumers', 'UserConsumerController@store'); //bond user to  type account consumer   max 1 account
    $router->post('/sellers', 'UserSellerController@store'); //bond user to  type account consumer  max 1 account
    $router->get('/{user_id}', 'UserController@getById'); //get id by id
});


$router->group([
    'prefix'=> 'transactions',
], function() use ($router) {
    $router->post('/', 'TransactionController@store');// insert user
    $router->get('/{transaction_id}', 'TransactionController@get'); //return base na query string

});

