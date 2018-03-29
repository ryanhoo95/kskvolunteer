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

//get staff profile
Route::post('/getStaffProfile', ['as' => 'api.getStaffProfile', 'uses' => 'ApiController@getStaffProfile']);

//edit staff profile
Route::post('/updateStaffProfile', ['as' => 'api.updateStaffProfile', 'uses' => 'ApiController@updateStaffProfile']);

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

// get history
Route::post('/getHistory', ['as' => 'api.getHistory', 'uses' => 'ApiController@getHistory']);

//get today participations
Route::post('/getTodayParticipations', ['as' => 'api.getTodayParticipations', 'uses' => 'ApiController@getTodayParticipations']);

//get participations by date
Route::post('/getParticipationsByDate', ['as' => 'api.getParticipationsByDate', 'uses' => 'ApiController@getParticipationsByDate']);

// get participants
Route::post('/getParticipants', ['as' => 'api.getParticipants', 'uses' => 'ApiController@getParticipants']);

// absent
Route::post('/absent', ['as' => 'api.absent', 'uses' => 'ApiController@absent']);

// present
Route::post('/present', ['as' => 'api.present', 'uses' => 'ApiController@present']);

// get volunteer details
Route::post('/getVolunteerDetails', ['as' => 'api.getVolunteerDetails', 'uses' => 'ApiController@getVolunteerDetails']);