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


//test
Route::post('/test', ['as' => 'api.test2', 'uses' => 'ApiController@test']);
Route::get('/users', ['as' => 'api.test', 'uses' => 'PagesController@api']);
Route::get('/user/{id}', ['as' => 'api.get', 'uses' => 'PagesController@show']);
Route::get('/testTime', ['as' => 'api.testTime', 'uses' => 'ApiController@testTime']);

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

//edit volunteer profile
Route::post('/updateVolunteerProfile', ['as' => 'api.updateVolunteerProfile', 'uses' => 'ApiController@updateVolunteerProfile']);

//upload profile image
Route::post('/uploadProfileImage', ['as' => 'api.uploadProfileImage', 'uses' => 'ApiController@uploadProfileImage']);

//reset password
Route::post('/resetPassword', ['as' => 'api.resetPassword', 'uses' => 'ApiController@resetPassword']);

//get today activities
Route::post('/getTodayActivities', ['as' => 'api.getTodayActivities', 'uses' => 'ApiController@getTodayActivities']);

//get activities by date
Route::post('/getActivitiesByDate', ['as' => 'api.getActivitiesByDate', 'uses' => 'ApiController@getActivitiesByDate']);

// join activity
Route::post('/joinActivity', ['as' => 'api.joinActivity', 'uses' => 'ApiController@joinActivity']);

// withdraw activity
Route::post('/withdrawActivity', ['as' => 'api.withdrawActivity', 'uses' => 'ApiController@withdrawActivity']);

// get active participation
Route::post('/getActiveParticipations', ['as' => 'api.getActiveParticipations', 'uses' => 'ApiController@getActiveParticipations']);

// get invited participants
Route::post('/getInvitedParticipants', ['as' => 'api.getInvitedParticipants', 'uses' => 'ApiController@getInvitedParticipants']);

// get volunteers for invite
Route::post('/getVolunteersForInvite', ['as' => 'api.getVolunteersForInvite', 'uses' => 'ApiController@getVolunteersForInvite']);

// send invitation
Route::post('/sendInvitation', ['as' => 'api.sendInvitation', 'uses' => 'ApiController@sendinvitation']);

// get pending invitations
Route::post('/getPendingInvitations', ['as' => 'api.getPendingInvitations', 'uses' => 'ApiController@getPendingInvitations']);

// reject invitation
Route::post('/rejectInvitation', ['as' => 'api.rejectInvitation', 'uses' => 'ApiController@rejectInvitation']);

// accept invitation
Route::post('/acceptInvitation', ['as' => 'api.acceptInvitation', 'uses' => 'ApiController@acceptInvitation']);
