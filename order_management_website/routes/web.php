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
    Route::get('/', 'OrderController@index')->name('order.index');
    Route::get('/create', 'OrderController@create')->name('order.create');
    Route::get('/{id}', 'OrderController@show')->name('order.show');
    Route::get('/{id}/edit', 'OrderController@edit')->name('order.edit');
    Route::post('/', 'OrderController@store')->name('order.store');
    Route::put('/{id}', 'OrderController@update')->name('order.update');
    Route::delete('/{id}', 'OrderController@delete')->name('order.delete');

    Route::get('/{id}/pdf', 'OrderController@pdf')->name('order.pdf');
});

Route::group(['prefix' => 'package'], function () {
    Route::post('/', 'PackageController@store')->name('package.store');
    Route::put('/{id}', 'PackageController@update');
    Route::delete('/{id}', 'PackageController@delete');
});

Route::group(['prefix' => 'cookie'], function () {
    Route::get('/', 'CookieController@index')->name('cookie.index');
    Route::post('/', 'CookieController@store');
    Route::put('/{id}', 'CookieController@update');
});

Route::group(['prefix' => 'case'], function () {
    Route::get('/', 'CaseController@index')->name('case.index');
    Route::post('/', 'CaseController@store');
    Route::put('/{id}', 'CaseController@update');
});

Route::group(['prefix' => 'pack'], function () {
    Route::get('/', 'PackController@index')->name('pack.index');
    Route::post('/', 'PackController@store');
    Route::put('/{id}', 'PackController@update');
});
