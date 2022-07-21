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

Route::get('/', 'WebController@index');

Auth::routes();

Route::group(['middleware' => ['web', 'auth']],function(){

	Route::get('/home', 'HomeController@index')->name('home');
    
    Route::resource('Parkir', 'Admin\ParkirController');
    Route::get('/CetakLaporan/{daterange}', 'Admin\ParkirController@cetakLaporan');
    Route::get('/UpdateParkir/{id}', 'Admin\ParkirController@update');
    Route::get('/HapusParkir/{id}', 'Admin\ParkirController@destroy');

    Route::resource('Pengguna', 'Admin\PenggunaController');
	Route::get('/HapusPengguna/{id}', 'Admin\PenggunaController@destroy');


});
