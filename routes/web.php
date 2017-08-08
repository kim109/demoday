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
    $uri = Auth::check() ? 'main' : 'login';
    return redirect($uri);
});

Auth::routes();

Route::get('/main', 'MainController@index')->name('main');
Route::get('/main/items', 'MainController@items');
Route::post('/main/investment', 'MainController@investment');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'AdminController@index');
    Route::get('setting', 'AdminController@getSetting');
    Route::patch('setting', 'AdminController@setSetting');

    Route::post('items', 'Admin\ItemController@store');
    Route::patch('items/{id}', 'Admin\ItemController@edit')->where('id', '[0-9]+');
    Route::delete('items/{id}', 'Admin\ItemController@remove')->where('id', '[0-9]+');

    Route::patch('items/{id}/event', 'AdminController@event');

    Route::get('results', 'Admin\ResultController@overview');
    Route::get('results/{id}', 'Admin\ResultController@detail')->where('id', '[0-9]+');
});
