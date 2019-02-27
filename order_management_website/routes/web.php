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
    return redirect('order');
});
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'order'], function () {
        Route::get('/{id}/pdf', 'OrderController@pdf')->name('order.pdf');

        Route::get('/', 'OrderController@index')->name('order.index');
        Route::get('/search', 'OrderController@search')->name('order.search');
        Route::get('/create', 'OrderController@create')->name('order.create');
        Route::get('/{id}', 'OrderController@show')->name('order.show');
        Route::get('/{id}/edit', 'OrderController@edit')->name('order.edit');
        Route::post('/', 'OrderController@store')->name('order.store');
        Route::put('/{id}', 'OrderController@update')->name('order.update');
        Route::delete('/{id}', 'OrderController@delete')->name('order.delete');
    });

    Route::group(['prefix' => 'package'], function () {
        Route::post('/excel', 'PackageController@excel')->name('package.excel');

        Route::post('/', 'PackageController@store')->name('package.store');
        Route::get('/search', 'PackageController@search')->name('package.search');
        Route::put('/{id}', 'PackageController@update');
        Route::delete('/{id}', 'PackageController@delete');
    });

    Route::group(['prefix' => 'cookie'], function () {
        Route::get('/', 'CookieController@index')->name('cookie.index');
        Route::post('/', 'CookieController@store');
        Route::put('/{id}', 'CookieController@update');
        Route::delete('/{id}', 'CookieController@delete');
    });

    Route::group(['prefix' => 'caseType'], function () {
        Route::get('/', 'CaseTypeController@index')->name('caseType.index');
        Route::post('/', 'CaseTypeController@store');
        Route::put('/{id}', 'CaseTypeController@update');
        Route::delete('/{id}', 'CaseTypeController@delete');
    });

    Route::group(['prefix' => 'pack'], function () {
        Route::get('/', 'PackController@index')->name('pack.index');
        Route::post('/', 'PackController@store');
        Route::put('/{id}', 'PackController@update');
        Route::delete('/{id}', 'PackController@delete');
    });

    Route::get('/management', function () {
        return view('management.index');
    });
});
