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


	Route::resource('Menu', 'Admin\MenuController');
    Route::get('/HapusMenu/{id}', 'Admin\MenuController@destroy');

    Route::resource('Website', 'Admin\WebsiteController');
    Route::get('/HapusWebsite/{id}', 'Admin\WebsiteController@destroy');

    Route::resource('Slide', 'Admin\SlideController');
    Route::get('/HapusSlide/{id}', 'Admin\SlideController@destroy');

    Route::resource('Pengguna', 'Admin\PenggunaController');
	Route::get('/HapusPengguna/{id}', 'Admin\PenggunaController@destroy');


});
