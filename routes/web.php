<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');

Auth::routes(['verify' => true]);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/tour', 'TourController@index')->name('tour.index');
Route::get('/tour-detail/{tour}/{slug}', 'TourController@show')->name('tour.show');
Route::get('/tour-package/{package}/{slug}', 'TourController@package')->name('tour.package');

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::get('/setting', 'UserController@setting')->name('setting');
    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::get('/edit-profile', 'UserController@editProfile')->name('edit-profile');

    Route::get('/checkout/{tour}/{slug}', 'OrderController@create')->name('order.create')->middleware('verified');
    Route::get('/order', 'OrderController@index')->name('order.index');
    Route::get('/order/{order}', 'OrderController@show')->name('order.show');
    Route::post('/order/{tour}', 'OrderController@store')->name('order.store');

    Route::patch('/update-avatar/{user}', 'UserController@updateAvatar')->name('update-avatar');
    Route::patch('/update-setting', 'UserController@updateSetting')->name('update-setting');
    Route::patch('/update-profile', 'UserController@updateProfile')->name('update-profile');

    Route::patch('/order/{order}', 'OrderController@update')->name('order.update');
    Route::put('/testimonial/{order}', 'OrderController@testimonial')->name('testimonial.update');

    Route::resource('order', 'OrderController')->except('create','store','edit');

    Route::group(['middleware' => ['can:admin']], function () {

        Route::resource('package', 'PackageController');
        Route::resource('gallery', 'GalleryController');
        Route::resource('company', 'CompanyController');
        Route::resource('tour', 'TourController')->except('show','index');
        Route::resource('user', 'UserController');

    });

});
