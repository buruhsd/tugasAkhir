<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => ['web']], function () {

	Route::get('/','HomeController@index');
    Route::auth();
	Route::get('/home', 'HomeController@index');
	Route::get('books/{book}/download',[
		'middleware'=>['auth','role:member'],
		'as'=>'books.download',
		'uses'=>'BooksController@download']);
	Route::get('books/{book}/baca',[
		'middleware'=>['auth','role:member'],
		'as'=>'books.baca',
		'uses'=>'BooksController@baca']);
	Route::get('settings/profile','SettingsController@profile');
	Route::get('settings/profile/edit','SettingsController@editProfile');
	Route::post('settings/profile/','SettingsController@updateProfile');
	Route::get('settings/password','SettingsController@editPassword');
	Route::post('settings/password','SettingsController@updatePassword');
	Route::get('auth/verify/{token}', 'Auth\AuthController@verify');
	Route::get('auth/send-verification', 'Auth\AuthController@sendVerification');
	Route::get('bukusaya', ['uses'=>'HomeController@bukusaya', 'as'=>'bukusaya']);
	//Route::get('books/{book}/show',[
	//	'middleware'=>['auth','role:member'],
	//	'as'=>'books.show',
	//	'uses'=>'GuestController@show']);
	Route::get('books/{book}/createcover', [
		'middleware'=>['auth','role:member'],
		'as'=>'books.createcover',
		'uses'=>'GuestController@createcover']);
	

	Route::group(['middleware' => 'auth'], function() {

	Route::resource('books', 'GuestController');
	

	});


//Route Admin
	Route::group(['prefix'=>'admin', 'middleware'=>['auth', 'role:admin']], function () {
		Route::resource('books', 'BooksController');
		Route::resource('members', 'MembersController', [
            'only'=>['index', 'show', 'destroy']
        ]);

		});


  });

