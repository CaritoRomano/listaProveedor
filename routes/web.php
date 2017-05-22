<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login'
]);

Route::get('/index', ['uses' => 'ProbandoController@view',
				'as'=> 'index' 
]);

Auth::routes();

Route::get('/home', ['uses' => 'HomeController@index', 'as' => 'home']);
Route::post('/actualizarLista', ['uses' => 'HomeController@actualizarLista', 'as' => 'actualizarLista']);

