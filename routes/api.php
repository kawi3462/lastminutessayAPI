<?php

use Illuminate\Http\Request;


Route::group([
    'prefix' => 'v1'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('reset', 'API\PasswordResetController@sendPin');
    Route::post('validatepin', 'API\PasswordResetController@validatePin');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {

       //User routes 
       Route::post('adduseravatar', 'AuthController@addUserAvatar');
       Route::post('getavatar','AuthController@getAvatars');
     
        Route::post('updatephone', 'AuthController@updatePhoneNumber');
 
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::post('loggedResetPassword','API\PasswordResetController@resetPasswordUserLoggedIn');


        Route::get('allorders', 'API\OrdersController@index');
        Route::post('neworder', 'API\OrdersController@insert');
		

        Route::post('uploadmaterials', 'API\OrderMaterialsController@uploadFiles');
        Route::post('deletematerials/{id}', 'API\OrderMaterialsController@deletefiles');
     Route::get('viewmaterials/{id}', 'API\OrderMaterialsController@vieworderfiles');

     Route::get('viewmaterials/{id}', 'API\OrderMaterialsController@vieworderfiles');


     Route::get('viewuserordermaterials/{email}', 'API\OrderMaterialsController@viewUserOrderfiles');



        Route::get('myorders/{email}', 'API\OrdersController@myuserorders');
        Route::post('updateorder/{id}', 'API\OrdersController@update');
        Route::get('vieworder/{id}', 'API\OrdersController@show');
        Route::delete('deleteorder/{id}', 'API\OrdersController@delete');

        //Referral routes

        Route::post('addReferral','API\ReferralController@store');

      



    });
});
