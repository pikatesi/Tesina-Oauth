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
    //return view('welcome');
    return view('index');
});


Route::get('login/google', 'GoogleLogin@redirectToAuthServer');
Route::get('login/google/callback', 'GoogleLogin@callbackEndpoint');

Route::get('login/fb', 'FbLogin@redirectToAuthServer');
Route::get('login/fb/callback', 'FbLogin@callbackEndpoint');

Route::get('login/passport', 'PassportLogin@redirectToAuthServer');
Route::get('login/passport/callback', 'PassportLogin@callbackEndpoint');
