<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: content-type');

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users', ['as' => 'api.test', 'uses' => 'PagesController@api']);
Route::get('/user/{id}', ['as' => 'api.get', 'uses' => 'PagesController@show']);

//login
Route::post('/login', ['as' => 'api.login', 'uses' => 'ApiController@login']);

//logout
Route::post('/logout', ['as' => 'api.logout', 'uses' => 'ApiController@logout']);

//register
Route::post('/register', ['as' => 'api.register', 'uses' => 'ApiController@register']);
