<?php

/**
 * Passport
 */
 Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'], function () {
     // AccessTokenController
     Route::post('token', 'AccessTokenController@issueToken');
 });

 /**
  * Auth
  */
 Route::group(['namespace' => '\App\Http\Controllers\Auth'], function () {
     // DestroyAccessTokenController
     Route::delete('token/destroy', 'DestroyAccessTokenController@destroy');

     // PasswordGrantClientController
     Route::get('password-grant-client', 'PasswordGrantClientController@showPasswordGrantClient');
 });
