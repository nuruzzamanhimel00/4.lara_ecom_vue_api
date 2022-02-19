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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// ### response send and website login ####
Route::get('/website-login', 'Auth\ApiAuthController@login')->name('website.login');
Route::get('/secure-login', 'Auth\ApiAuthController@secureLogin')->name('secure.login');

Route::get('/home', 'HomeController@index')->name('home');
