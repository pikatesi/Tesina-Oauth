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


Route::get('oidc/discover', 'Oidc@discovery');
Route::get('login/oidc/oauth0', 'Oidc@login');
Route::get('login/oidc/oauth0/redirect', 'Oidc@redirect');


Route::get('login/occ/oauth0', 'OicClient@login');
Route::get('login/occ/oauth0/redirect', 'OicClient@redirect');



Route::get('login/oidc/local', 'OidcLocalServer@login');
Route::get('login/oidc/local/redirect', 'OidcLocalServer@redirect');