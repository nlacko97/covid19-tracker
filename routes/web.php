<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/{country?}', 'DashboardController@index')->name('index');

Route::group(['prefix' => 'testing'], function () {
    Route::get('select-1', 'TestingController@select1')->name('select1');

    Route::get('insert-1', 'TestingController@insert1')->name('insert1');
});