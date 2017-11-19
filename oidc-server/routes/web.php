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

Route::post('/oauth/token', 'OidcServer@token');
Route::any('/oauth/authorize', 'OidcServer@authorizeAction');
Route::get('/.well-known/openid-configuration', 'OidcServer@configuration');
Route::get('/.well-known/jwks.json', 'OidcServer@jwks');
Route::get('/openid/userinfo', 'OidcServer@userinfo');
