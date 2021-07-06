<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Api!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

    Route::post('login', 'Api\AuthApi@login');
    Route::post('subscribe', 'Api\AuthApi@subscribe');
    Route::post('contact', 'Api\AuthApi@ContactMessage');
    Route::post('register', 'Api\AuthApi@register');
    Route::get('profile', 'Api\PetController@Profile');
    Route::get('sample', 'Api\PetController@Sample');


    Route::middleware(['authApi'])->group(function ()
    {
        Route::get('handShake', 'Api\UserController@handShake');
        Route::post('save_card','Api\UserController@SaveCard');
        Route::post('coupon','Api\UserController@Coupon');
        Route::post('checkCode','Api\UserController@CheckCode');
        Route::post('cancelMembership','Api\UserController@CancelMembership');
        Route::post('documents','Api\UserController@Documents');
        Route::post('fetchDocuments','Api\UserController@FetchDocuments');
        Route::group(["prefix"=>"pet"],function() {
            Route::get('','Api\PetController@index');
            Route::post('/create','Api\PetController@Create');
            Route::post('/update','Api\PetController@Update');
            Route::post('/delete','Api\PetController@Delete');
        });

    });