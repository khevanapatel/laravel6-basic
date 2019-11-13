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
    return view('welcome');
});

Auth::routes(['verify' => true]);


/****************************** User Routes *****************************/
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['user']], function () {
	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::post('/updateprofile', 'ProfileController@update')->name('updateprofile');
	Route::post('/changepassword', 'ProfileController@changePassword')->name('changepassword');
});

/************************************ Admin Routes ****************************************/
Route::group(['middleware' => ['admin']], function () {
	Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');

	// User module //
	Route::get('/user-list', 'Admin\UserController@getList')->name('user.list');
	Route::post('/user-action/{id}', 'Admin\UserController@action')->name('user.action');

	// Setting Module //
	Route::get('/setting/list','Admin\SettingController@getList')->name('setting.list');
	Route::get('/setting/edit/{id}','Admin\SettingController@edit')->name('setting.edit');
	Route::post('/setting/update/{id}','Admin\SettingController@update')->name('setting.update');
});