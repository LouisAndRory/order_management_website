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
    return view('index');
});

Route::group(['prefix' => 'order'], function () {
    Route::get('/create', 'OrderController@create');
    Route::get('/{id}', 'OrderController@show');
    Route::get('/{id}/edit', 'OrderController@edit');
    Route::post('/', 'OrderController@store');
    Route::put('/{id}', 'OrderController@update');
});

Route::group(['prefix' => 'cookie'], function () {
    Route::get('/', 'CookieController@index');
    Route::post('/', 'CookieController@store');
    Route::put('/{id}', 'CookieController@update');
});

Route::group(['prefix' => 'case'], function () {
    Route::get('/', 'CaseController@index');
    Route::post('/', 'CaseController@store');
    Route::put('/{id}', 'CaseController@update');
});

Route::group(['prefix' => 'pack'], function () {
    Route::get('/', 'PackController@index');
    Route::post('/', 'PackController@store');
    Route::put('/{id}', 'PackController@update');
});
