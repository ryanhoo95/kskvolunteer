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

//test
Route::post('/test', ['as' => 'api.test2', 'uses' => 'ApiController@test']);

//login
Route::post('/login', ['as' => 'api.login', 'uses' => 'ApiController@login']);

//logout
Route::post('/logout', ['as' => 'api.logout', 'uses' => 'ApiController@logout']);

//check unique email
Route::post('/checkUniqueEmail', ['as' => 'api.checkUniqueEmail', 'uses' => 'ApiController@checkUniqueEmail']);

//check unique ic passport
Route::post('/checkUniqueICPassport', ['as' => 'api.checkUniqueICPassport', 'uses' => 'ApiController@checkUniqueICPassport']);

//register
Route::post('/register', ['as' => 'api.register', 'uses' => 'ApiController@register']);

//get volunteer profile
Route::post('/getVolunteerProfile', ['as' => 'api.getVolunteerProfile', 'uses' => 'ApiController@getVolunteerProfile']);
