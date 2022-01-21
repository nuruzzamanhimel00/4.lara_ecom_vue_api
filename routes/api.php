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

// Route::apiResource('/userslider', 'Fontend\Api\SliderController');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'admin','namespace'=>"Admin\Api\Auth"], function(){

    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::post('access-token', 'LoginController@accessToken')->name('admin.access.token');


});
Route::group(['prefix'=>'admin','namespace'=>"Admin\Api"], function(){

    // Product
    Route::apiResource('product', 'ProductController');
    Route::apiResource('category', 'CategoryController');
    Route::apiResource('brand', 'BrandController');

    Route::apiResource('slider', 'SliderController');

    Route::get('product-status', 'ProductController@getProductStatus');
    Route::get('products', 'ProductController@getAllProducts');
    Route::get('slider-lastprority', 'SliderController@lastprority');
    Route::get('slider-status', 'SliderController@sliderStatus');

    Route::get('category-withproductcount', 'CategoryController@catWithProductCount');
    Route::get('brand-withProductCount', 'BrandController@barndWithProductCount');

});
Route::group(['prefix'=>'/','namespace'=>"Fontend\Api"], function(){

    Route::apiResource('slider', 'SliderController');
    Route::apiResource('product', 'ProductController');
    Route::get('products-paginate', 'ProductController@getAllProductsPaginate');

    //brand
    Route::get('brand/{brandid}', 'BrandController@brandByBrandid');
    Route::get('brnadsProducts/{brandid}', 'BrandController@getBrandsProducts');
    //category
    Route::get('category/{catid}', 'CategoryController@getCetegoryByCatid');
    Route::get('categoryProducts/{catid}', 'CategoryController@getCategoryWiseProducts');

    //auth
    Route::post('registration', 'Auth\AuthController@registration');
    Route::post('login', 'Auth\AuthController@login');
    Route::post('access-token', 'Auth\AuthController@accessToken');
    // fontend user profile
    Route::post('profile-update', 'UserProfileController@userProfileUpdate');

});



// Route::group(['namespace'=>'Fontend\Api'],function(){
//     Route::apiResource('slider', 'SliderController');
// });

// Route::get('admin/login', 'Admin\Api\Auth\LoginController@index')->name('admin.login');
