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

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'MainController@index')->name('main');
Route::get('/items', 'MainController@items');
Route::post('/items/{id}/investment', 'MainController@investment')->where('id', '[0-9]+');
Route::post('/items/{id}/event', 'MainController@event')->where('id', '[0-9]+');
Route::get('/status', 'MainController@status');
Route::get('/results', 'MainController@results');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', 'SettingController@index')->name('admin');
    Route::get('setting', 'SettingController@getSetting');
    Route::patch('setting', 'SettingController@setSetting');
    Route::get('experts/options', 'SettingController@searchAD');
    Route::delete('reset', 'SettingController@reset');

    Route::post('items', 'ItemController@store');
    Route::patch('items/{id}', 'ItemController@edit')->where('id', '[0-9]+');
    Route::delete('items/{id}', 'ItemController@remove')->where('id', '[0-9]+');
    Route::patch('items/{id}/event', 'ItemController@event')->where('id', '[0-9]+');

    Route::get('results', 'ResultController@overview');
    Route::get('results/{id}', 'ResultController@detail')->where('id', '[0-9]+');
});
