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

// Route::get('/', function () {
//     return view('pages.index');
// });


Route::get('/', 'PagesController@index');

Route::get('logout', 'Auth\LoginController@logout');

Auth::routes();

Route::resource('profile', 'ProfileController');

Route::get('/reset_password', 'ProfileController@resetPassword');

Route::put('/reset_password/{id}', ['as' => 'profile.update_password', 'uses' => 'ProfileController@updatePassword']);

Route::get('/test/{type}/{id}', ['as' => 'user.test', 'uses' => 'PagesController@test']);

//user management
Route::get('/user/{type}', ['as' => 'user.index', 'uses' => 'UserController@index']);
Route::get('/user/{type}/{id}/profile', ['as' => 'user.show', 'uses' => 'UserController@show']);
Route::put('/user/{type}/{id}/profile/{action}', ['as' => 'user.update', 'uses' => 'UserController@update']);
Route::get('/user/{type}/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
Route::post('/user/{type}', ['as' => 'user.store', 'uses' => 'UserController@store']);

