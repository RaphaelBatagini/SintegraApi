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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'Auth\AuthController@getLogin');

Route::get('sintegra', ['as' => 'sintegra', 'uses' => 'SintegrasController@index']);
Route::get('sintegra/find-cnpj', ['as' => 'sintegra/find-cnpj', 'uses' => 'SintegrasController@findCnpj']);
Route::get('sintegra/destroy/{id}', 'SintegrasController@destroy');
Route::post('sintegra/create', 'SintegrasController@create');
Route::get('sintegra/create', 'SintegrasController@create');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// API Rest routes
Route::post('api/find-cnpj', ['uses' => 'SintegraRestController@create','middleware'=>'simpleauth']);
Route::post('api/destroy', ['uses' => 'SintegraRestController@destroy','middleware'=>'simpleauth']);
Route::post('api', ['uses' => 'SintegraRestController@index','middleware'=>'simpleauth']);
