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


Route::group(['prefix' => 'user', 'userId' => '[0-9]+'], function () {
    Route::put('/updated/{userId}','API\AuthController@updatedUser');
    Route::delete('/deleted/{userId}', 'API\AuthController@deletedUser');
});

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');

Route::group(['middleware' => 'auth:api','userId' => '[0-9]+'], function(){
    Route::post('get-details', 'API\AuthController@getDetails');
});