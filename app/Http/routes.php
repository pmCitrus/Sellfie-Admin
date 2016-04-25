<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('datatables', ['as' => 'datatables', 'uses' => 'App\Http\Controllers\DatatablesController@getIndex']);
Route::get('datatables_data', ['as' => 'datatables.data', 'uses' => 'App\Http\Controllers\DatatablesController@anyData']);

Route::group(['middleware' => ['web']], function () {
    Route::group(['namespace' => 'App\V1\Controllers'], function() {
        // Authorization
        Route::get('login', ['as' => 'auth.login.form', 'uses' => 'SessionController@getLogin']);
        Route::post('login', ['as' => 'auth.login.attempt', 'uses' => 'SessionController@postLogin']);
        Route::get('logout', ['as' => 'auth.logout', 'uses' => 'SessionController@getLogout']);

        // Registration
        Route::get('register', ['as' => 'auth.register.form', 'uses' => 'RegistrationController@getRegister']);
        Route::post('register', ['as' => 'auth.register.attempt', 'uses' => 'RegistrationController@postRegister']);

        // Activation
        Route::get('activate/{code}', ['as' => 'auth.activation.attempt', 'uses' => 'RegistrationController@getActivate']);
        Route::get('resend', ['as' => 'auth.activation.request', 'uses' => 'RegistrationController@getResend']);
        Route::post('resend', ['as' => 'auth.activation.resend', 'uses' => 'RegistrationController@postResend']);

        // Password Reset
        Route::get('password/reset/{code}', ['as' => 'auth.password.reset.form', 'uses' => 'PasswordController@getReset']);
        Route::post('password/reset/{code}', ['as' => 'auth.password.reset.attempt', 'uses' => 'PasswordController@postReset']);
        Route::get('password/reset', ['as' => 'auth.password.request.form', 'uses' => 'PasswordController@getRequest']);
        Route::post('password/reset', ['as' => 'auth.password.request.attempt', 'uses' => 'PasswordController@postRequest']);
        
        Route::group(['middleware' => 'sentinel.auth'], function() {
            
            //Dashboard
            Route::get('dashboard/{time_period?}', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
            Route::post('dashboard', ['as' => 'dashboard_custom', 'uses' => 'DashboardController@show']);
            
            //UM
            Route::get('users', ['as' => 'users', 'uses' => 'AdminUserController@index']);
            Route::get('users/new', ['as' => 'users_new_view', 'uses' => 'AdminUserController@storeView']);
            Route::post('users/new', ['as' => 'users_new', 'uses' => 'AdminUserController@store']);
            Route::get('users/{id}/view', ['as' => 'users_view', 'uses' => 'AdminUserController@updateView']);
            Route::post('users/update', ['as' => 'users_update', 'uses' => 'AdminUserController@update']);
            Route::get('users/{id}/delete', ['as' => 'users_delete', 'uses' => 'AdminUserController@delete']);
            Route::post('users/ban', ['as' => 'users_ban', 'uses' => 'AdminUserController@ban']);
            
            //Sellers
            Route::get('sellers/{seller_status}', ['as' => 'sellers', 'uses' => 'SellerController@index']);
            Route::get('sellers/{seller_status}/{id}/view', ['as' => 'sellers_view', 'uses' => 'SellerController@updateView']);
            Route::post('sellers/{seller_status}', ['as' => 'sellers_view', 'uses' => 'SellerController@update']);
            Route::get('sellers/{seller_status}/{id}/enable', ['as' => 'sellers_enable', 'uses' => 'SellerController@enable']);
            Route::get('sellers/{seller_status}/{id}/delete', ['as' => 'sellers_delete', 'uses' => 'SellerController@delete']);
            Route::post('sellers/{seller_status}/ban', ['as' => 'sellers_ban', 'uses' => 'SellerController@ban']);
            
            Route::get('products/{product_status}', ['as' => 'products', 'uses' => 'ProductController@index']);
            Route::get('products/{product_status}/{id}/approve', ['as' => 'products_approve', 'uses' => 'ProductController@approve']);
            Route::get('products/{product_status}/{id}/enable', ['as' => 'products_enable', 'uses' => 'ProductController@edit']);
            Route::get('products/{product_status}/{id}/disable', ['as' => 'products_disable', 'uses' => 'ProductController@edit']);
            Route::get('products/{product_status}/{id}/ban', ['as' => 'products_ban', 'uses' => 'ProductController@destroy']);
            Route::get('products/{product_status}/{id}/ignore', ['as' => 'products_ignore', 'uses' => 'ProductController@ignore']);
            
            Route::get('collect_links/{collect_link_status}', ['as' => 'collect_links', 'uses' => 'CollectLinkController@index']);
            Route::get('collect_links/{collect_link_status}/{id}/approve', ['as' => 'collect_links_approve', 'uses' => 'CollectLinkController@approve']);
            Route::get('collect_links/{collect_link_status}/{id}/enable', ['as' => 'collect_links_enable', 'uses' => 'CollectLinkController@edit']);
            Route::get('collect_links/{collect_link_status}/{id}/disable', ['as' => 'collect_links_disable', 'uses' => 'CollectLinkController@edit']);
            Route::get('collect_links/{collect_link_status}/{id}/ban', ['as' => 'collect_links_ban', 'uses' => 'CollectLinkController@destroy']);
            Route::get('collect_links/{collect_link_status}/{id}/ignore', ['as' => 'collect_links_ignore', 'uses' => 'CollectLinkController@ignore']);
            
            Route::get('orders/{status_code}', ['as' => 'orders', 'uses' => 'OrderController@index']);
            Route::get('orders/{status_code}/{id}/{action_type}', ['as' => 'orders_refund', 'uses' => 'OrderController@refund']);
            
            Route::get('collect_payments/{status_code}', ['as' => 'collect_payments', 'uses' => 'CollectPaymentController@index']);
            
            Route::get('transactions', ['as' => 'transactions', 'uses' => 'TransactionController@index']);
            Route::get('transactions/{id}/view', ['as' => 'transactions_view', 'uses' => 'TransactionController@show']);
            
            Route::get('commission_settings', ['as' => 'commission_settings', 'uses' => 'CommissionSettingsController@index']);
            Route::post('commission_settings', ['as' => 'commission_settings', 'uses' => 'CommissionSettingsController@store']);
            
            Route::get('rod_settings', ['as' => 'rod_settings', 'uses' => 'RodSettingsController@index']);
            Route::post('rod_settings', ['as' => 'rod_settings', 'uses' => 'RodSettingsController@store']);
            
            Route::get('settlements/upload', ['as' => 'settlements_upload_view', 'uses' => 'SettlementController@index']);
            Route::post('settlements/upload', ['as' => 'settlements_upload', 'uses' => 'SettlementController@store']);
            Route::get('settlements/download', ['as' => 'settlements_download_view', 'uses' => 'SettlementController@downloadView']);
            Route::post('settlements/download', ['as' => 'settlements_download', 'uses' => 'SettlementController@download']);
            
            Route::get('kyc_upload/{ref_id}', ['as' => 'kyc_upload_view', 'uses' => 'KycUploadController@index']);
            Route::post('kyc_upload/{ref_id}', ['as' => 'kyc_upload', 'uses' => 'KycUploadController@store']);
            
            Route::get('kyc/{kyc_status}', ['as' => 'kyc', 'uses' => 'KycController@index']);
            Route::get('kyc/{kyc_status}/{ref_id}/view', ['as' => 'kyc_view', 'uses' => 'KycController@show']);
            Route::get('kyc/{kyc_status}/{ref_id}/approve', ['as' => 'kyc_approve', 'uses' => 'KycController@update']);
            Route::get('kyc/{kyc_status}/{ref_id}/reject', ['as' => 'kyc_approve', 'uses' => 'KycController@update']);
            
            Route::get('reports/{report_type}', ['as' => 'reports', 'uses' => 'ReportController@index']);
        });
    });
});

