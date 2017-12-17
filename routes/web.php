<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function (Router $router) {
    $router->get('/connections', 'RedisController@connections');
    $router->get('/scan', 'RedisController@scan');
    $router->delete('/keys', 'RedisController@destroy');
    $router->post('/keys', 'RedisController@store');
    $router->put('/keys', 'RedisController@update');
    $router->get('/key', 'RedisController@key');
    $router->get('/info', 'RedisController@info');
    $router->delete('/keys/item', 'RedisController@remove');
    $router->put('/expire', 'RedisController@expire');
    $router->post('/eval', 'RedisController@eval');
});

Route::get('/{view?}', 'RedisController@index')->where('view', '(.*)');
