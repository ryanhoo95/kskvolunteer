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
Route::get('/user/{type}/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
Route::post('/user/{type}', ['as' => 'user.store', 'uses' => 'UserController@store']);
Route::put('/user/{type}/{id}/profile/{action}', ['as' => 'user.update', 'uses' => 'UserController@update']);

//activity management (activity type)
Route::get('/activity_type', ['as' => 'activity_type.index', 'uses' => 'ActivityTypeController@index']);
Route::get('/activity_type/create', ['as' => 'activity_type.create', 'uses' => 'ActivityTypeController@create']);
Route::get('/activity_type/{id}', ['as' => 'activity_type.show', 'uses' => 'ActivityTypeController@show']);
Route::post('/activity_type', ['as' => 'activity_type.store', 'uses' => 'ActivityTypeController@store']);
Route::get('/activity_type/{id}/edit', ['as' => 'activity_type.edit', 'uses' => 'ActivityTypeController@edit']);
Route::put('/activity_type/{id}/{action}', ['as' => 'activity_type.update', 'uses' => 'ActivityTypeController@update']);

//activity management (activity)
Route::get('/activity', ['as' => 'activity.index', 'uses' => 'ActivityController@index']);
Route::get('/activity/create', ['as' => 'activity.create', 'uses' => 'ActivityController@create']);
Route::get('/activity/{id}', ['as' => 'activity.show', 'uses' => 'ActivityController@show']);
Route::post('/activity', ['as' => 'activity.store', 'uses' => 'ActivityController@store']);
Route::get('/activity/{id}/edit', ['as' => 'activity.edit', 'uses' => 'ActivityController@edit']);
Route::put('/activity/{id}/{action}', ['as' => 'activity.update', 'uses' => 'ActivityController@update']);

