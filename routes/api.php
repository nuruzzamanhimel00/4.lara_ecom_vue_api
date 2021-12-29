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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'admin','namespace'=>"Admin\Api\Auth"], function(){

    Route::post('login', 'LoginController@login')->name('admin.login');
});

// Route::get('admin/login', 'Admin\Api\Auth\LoginController@index')->name('admin.login');
