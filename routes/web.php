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
