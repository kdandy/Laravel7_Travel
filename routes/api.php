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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/check-transaction/{id}', 'OrderController@checkTransaction')->name('check-transaction');

Route::group(['as' => 'api.'], function(){
    Route::get('/users', 'ApiController@users')->name('users');
    Route::get('/tour-gallery/{id}', 'ApiController@tourGallery')->name('tour-gallery');
    Route::get('/order/{id}', 'ApiController@order')->name('order');
    Route::get('/gallery', 'ApiController@gallery')->name('gallery');
});
