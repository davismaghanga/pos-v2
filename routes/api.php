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

#authentication route
Route::group(['namespace'=>'Api'],function (){
    Route::post('authenticate','AuthController@authenticate');

    #any endpoints relating to sales
    Route::group(['namespace'=>'Sales'],function (){
        Route::get('products','SalesController@products');
        Route::post('get_customer','SalesController@getCustomer');

        Route::post('complete_sale','SalesController@completeSale');
    });
});
