<?php

use Illuminate\Http\Request;

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

// Guest User API
Route::group(['prefix' => 'user', 'namespace' => 'Api'], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
});

// Authenticated User API (Master)
// Route::group(['middleware' => 'auth:api', 'prefix' => 'master', 'namespace' => 'Api'], function () {
Route::group(['prefix' => 'master', 'namespace' => 'Api'], function () {
    Route::get('/user', 'Master\UserController@index');
    Route::post('/user', 'Master\UserController@store');
    Route::get('/user/{id}', 'Master\UserController@show');
    Route::put('/user/{id}', 'Master\UserController@update');
    Route::delete('/user/{id}', 'Master\UserController@destroy');
});