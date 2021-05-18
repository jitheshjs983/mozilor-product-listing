<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1'  , function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers', 'middleware' => ['jwt.token.expiry'] ], function ($api) {
        $api->get('/products','ProductController@get_products');
    });
    $api->group(['namespace' => 'App\Http\Controllers' ], function ($api) {
        $api->post('/login','UserController@login');
        $api->post('/register','UserController@register');
    });
    $api->group(['namespace' => 'App\Http\Controllers','middleware' => 'jwt.token.expiry' ], function ($api) {
        $api->get('/dashboard','ProductController@dashboard');
    });
});

