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

$router->get('/auth/login', 'AuthController@login');

$router->group(['middleware' => 'jwt-auth'], function($router)
{
    $router->get('/user', 'UserController@index');
    $router->get('/user/{id}', 'UserController@getUser');
});
    $router->get('/reservation/{hotel_id}', 'ReservationController@index');