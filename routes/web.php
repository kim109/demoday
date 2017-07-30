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
    return view('main');
});

Auth::routes();

Route::get('/main', 'MainController@index')->name('main');
Route::get('/main/items', 'MainController@items');
Route::post('/main/investment', 'MainController@investment');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'AdminController@index');
    Route::get('setting', 'AdminController@getSetting');
    Route::patch('setting', 'AdminController@setSetting');

    Route::post('items', 'AdminController@storeItem');
    Route::get('items/{id}', 'AdminController@getItem');
    Route::patch('items/{id}', 'AdminController@editItem');
    Route::delete('items/{id}', 'AdminController@removeItem');
});
