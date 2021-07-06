<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Route::get('/','HomeController@index');

    Route::get('/contact', 'AdminController@ContactMessageView');

    Route::get('/home','AdminController@index')->name('home');

    Route::group(['middleware'=>['auth','admin']] , function() {
        Route::get('/users','AdminController@Users')->name('users');
        Route::get('/pets','AdminController@Pets')->name('pets');
        Route::get('/pets/view','AdminController@Pet')->name('pets.view');
        Route::get('/pets/my-pets','AdminController@MyPets')->name('pets.my-pets');
        Route::get('subscribers','AdminController@SubscriberList')->name('subscribers');
        Route::get('sample','AdminController@Sample')->name('sample');
        Route::get('coupon','AdminController@Coupon')->name('coupon');
        Route::post('coupon_save','AdminController@Coupon_Save')->name('coupon.save');
        Route::post('qrCodeUpdate','AdminController@QrUpdate')->name('qr.update');
    });

    Auth::routes();



