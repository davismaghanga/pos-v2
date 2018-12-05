<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//use App\Facades\SMS;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/verifyLogin', 'AuthController@authenticate');

Route::post('/registerBusiness', 'BusinessController@register');

AdvancedRoute::controller('/home', 'HomeController');

AdvancedRoute::controller('/users', 'UserController');
Route::post('reset_password', 'UserController@reset');


AdvancedRoute::controller('/sales', 'SalesController');
Route::post('load_customer_loyalty','SalesController@getCutomerLoyalty');

AdvancedRoute::controller('/receipts', 'ReceiptController');

AdvancedRoute::controller('/products', 'ProductsController');

AdvancedRoute::controller('/services', 'ServicesController');

AdvancedRoute::controller('/customers', 'CustomersController');

AdvancedRoute::controller('/suppliers', 'SuppliersController');

AdvancedRoute::controller('/balances', 'BalancesController');

AdvancedRoute::controller('/sms', 'CanSendSmsController');

AdvancedRoute::controller('/orders', 'SheduledController');

AdvancedRoute::controller('/sReports', 'SalesReportsController');

AdvancedRoute::controller('/settings', 'SettingsController');

AdvancedRoute::controller('/transactions', 'TransactionsController');

AdvancedRoute::controller('/rTransactions', 'ReceiptTransactionsController');

AdvancedRoute::controller('/mpesa','MpesaController');

AdvancedRoute::controller('/categories_report','CategoriesReport');


//
AdvancedRoute::controller('/error', 'ErrorController');
Route::post('export_category_report','CategoriesReport@ExportExcelData');
//Mpesa sms route
Route::post('/notify_mpesa','NotifyController@notify');

//test

Route::get('/admin', function () {
    return view('admin/login');
});

Route::post('/admin/auth', 'SysAuthController@authenticate');

Route::get('/admin/logout', 'SysAuthController@logout');
Route::post('get_the_category_details','CategoriesReport@retrieveThem');
//AdvancedRoute::controller('admin/home', 'SysHomeController');
//AdvancedRoute::controller('admin/businesses', 'SysBusinessController');

//admin routes
    Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth', 'adminAccess']],function(){

        //Dashboard routes routes
        Route::group([],function(){
            Route::get('home','SysHomeController@index');
        });

        //businesses management routes routes
        Route::group([],function(){
            Route::get('businesses', 'SysBusinessController@index');
            Route::post('update_units', 'SysBusinessController@updateUnits');
            Route::post('fetch_update_history', 'SysBusinessController@fetchUpdatesHistory');
        });

        //logs routes
        Route::group([],function(){
            Route::get('logs','LogsController@index');

        });
    });

//text routes for the test controller
Route::get('test', 'TestController@sms');


//Clear Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/migrate', function() {
    $exitCode = Artisan::call('migrate');
    return '<h1>Migrations migrated: </h1>'.$exitCode;
});