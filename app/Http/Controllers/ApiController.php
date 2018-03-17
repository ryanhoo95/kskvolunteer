<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AppHelper;
use App\User;
use App\UserType;
use App\VolunteerProfile;
use Carbon\Carbon;
use App\Activity;
use App\ActivityType;
use App\Participation;
use App\Invitation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    //template
    public function template(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {

        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //test
    public function test(Request $request) {
        //format ic password
        $ic_passport = $request->input('ic_passport');
        $ic_passport_replace = str_replace('-', '', $ic_passport);
        $ic_passport_formmated = strtoupper($ic_passport_replace);

        $data = [
            'result' => $ic_passport_formmated
        ];

        return response()->json($data);
    }

    //test time
    public function testTime() {
        $now = Carbon::now();
        $date = "15 Mar 2018";
        $time = "8:00 PM";
        $date_time = $date." ".$time;
        $parse = Carbon::parse($date_time);
        $compare = ($now > $parse);  

        $data = [
            'now' => $now,
            'parse' => $parse,
            'compare' => $compare
        ];

        return response()->json($data);
    }

    //login
    public function login(Request $request) {
        $user = User::where('email', $request->input('email'))->get()->first();

        if($user) {
            if($user->status == "I") {
                $data = [
                    'status' => 'fail',
                    'message' => 'Your account has been deactivated. Please contact administrator for assistance.'
                ];
            }
            else if($user->api_token != null) {
                $data = [
                    'status' => 'fail',
                    'message' => 'Your account has been login in other device. In you insist to login from this device, please logout from other device first.'
                ];
            }
            else if(Hash::check($request->input('password'), $user->password)) {
                $api_token = bcrypt($user->user_id.time());
                $user->api_token = $api_token;
                $user->save();

                $user->image_url = AppHelper::getProfileStorageUrl().$user->profile_image;

                $data = [
                    'status' => 'success',
                    'data' => $user
                ];
            }
            else {
                $data = [
                    'status' => 'fail',
                    'message' => 'Incorrect password.'
                ];
            }
        }
        else {
            $data = [
                'status' => 'fail',
                'message' => 'User does not exist.'
            ];
        }

        return response()->json($data);
    }

    //logout
    public function logout(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $user->api_token = null;
            $user->save();

            $data = [
                'status' => 'success'
            ];
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //check for unique email
    public function checkUniqueEmail(Request $request) {
        //check for unique email
        $userByEmail = User::where('email', $request->input('email'))->get()->first();

        if($userByEmail) {
            $data = [
                'status' => 'fail',
                'message' => 'This email is already taken. Please use another email.'
            ];
        }
        else {
            $data = [
                'status' => 'success',
                'message' => 'This email can be used.'
            ];
        }

        return response()->json($data);
    }

    //check for unique ic passport
    public function checkUniqueICPassport(Request $request) {
        //check for unique ic passport
        $userByIc = User::where('ic_passport', $request->input('ic_passport'))->get()->first();

        if($userByIc) {
            $data = [
                'status' => 'fail',
                'message' => 'This IC or passport no. is already taken. Please contact administrator for assistance.'
            ];
        }
        else {
            $data = [
                'status' => 'success',
                'message' => 'This IC or passport no. can be used.'
            ];
        }

        return response()->json($data);
    }

    //register
    public function register(Request $request) {
        //check for unique email
        // $userByEmail = User::where('email', $request->input('email'))->get()->first();

        // if($userByEmail) {
        //     $data = [
        //         'status' => 'fail',
        //         'message' => 'This email is already taken. Please use another email.'
        //     ];

        //     return response()->json($data);
        // }

        // //check for unique ic passport
        // $userByIc = User::where('ic_passport', $request->input('ic_passport'))->get()->first();

        // if($userByIc) {
        //     $data = [
        //         'status' => 'fail',
        //         'message' => 'This IC or passport no. is already taken. Please contact administrator for assistance.'
        //     ];

        //     return response()->json($data);
        // }

        //format ic passport
        $ic_passport = $request->input('ic_passport');
        $ic_passport_replace = str_replace('-', '', $ic_passport);
        $ic_passport_formmated = strtoupper($ic_passport_replace);

        //register the user if checking passed
        $user = new User;
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->full_name = $request->input('full_name');
        $user->profile_name = $request->input('profile_name');
        $user->gender = $request->input('gender');
        $user->ic_passport = $ic_passport_formmated;
        $user->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d');
        $user->address = $request->input('address');
        $user->phone_no = $request->input('phone_no');
        $user->profile_image = "no_image.png";
        $user->status = "A";
        $user->usertype = "4";

        $user->save();

        //get the newly created user
        $insertedUser = User::where('email', $request->input('email'))->get()->first();
        $api_token = bcrypt($insertedUser->user_id + time());
        $insertedUser->api_token = $api_token;
        $insertedUser->save();

        //save the volunteer profile
        $volunteerProfile = new VolunteerProfile;
        $volunteerProfile->emergency_contact = $request->input('emergency_contact');
        $volunteerProfile->emergency_name = $request->input('emergency_name');
        $volunteerProfile->emergency_relation = $request->input('emergency_relation');
        $volunteerProfile->user_id = $insertedUser->user_id;
        $volunteerProfile->occupation = $request->input('occupation');
        $volunteerProfile->occupation_remark = $request->input('occupation_remark');
        $volunteerProfile->medium = $request->input('medium');
        $volunteerProfile->medium_remark = $request->input('medium_remark');
        $volunteerProfile->total_volunteer_duration = 0;
        $volunteerProfile->blacklisted_number = 0;
        $volunteerProfile->save();


        $data = [
            'status' => 'success',
            'data' => $insertedUser
        ];
        return response()->json($data);
    }

    //get volunteer profile
    public function getVolunteerProfile(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get()->first();

        if($user) {
            $volunteerProfile = VolunteerProfile::where('user_id', $user->user_id)->get()->first();

            if($volunteerProfile) {
                $profile = new User;
                $profile->user_id = $user->user_id;
                $profile->full_name = $user->full_name;
                $profile->profile_name = $user->profile_name;
                $profile->join_date = Carbon::parse($user->created_by)->format('d M Y');
                $profile->email = $user->email;
                $profile->date_of_birth = Carbon::parse($user->date_of_birth)->format('d M Y');
                $profile->date_of_birth_iso = Carbon::parse($user->date_of_birth)->format('Y-m-d');;

                if($user->gender == "M") {
                    $profile->gender = "Male";
                }
                else {
                    $profile->gender = "Female";
                }

                $profile->ic_passport = $user->ic_passport;
                $profile->address = $user->address;
                $profile->phone_no = $user->phone_no;
                $profile->profile_image = AppHelper::getProfileStorageUrl().$user->profile_image;
                $profile->phone_no = $user->phone_no;
                $profile->emergency_contact = $volunteerProfile->emergency_contact;
                $profile->emergency_name = $volunteerProfile->emergency_name;
                $profile->emergency_relation = $volunteerProfile->emergency_relation;

                $data = [
                    'status' => 'success',
                    'data' => $profile
                ];
            }
            else {
                $data = [
                    'status' => 'fail',
                    'message' => 'Error in retrieving data.'
                ];
            }
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //update volunteer profile
    public function updateVolunteerProfile(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $volunteerProfile = VolunteerProfile::where('user_id', $user->user_id)->get()->first();

            if($volunteerProfile) {
                //save user data
                $user->full_name = $request->input('full_name');
                $user->profile_name = $request->input('profile_name');
                $user->gender = $request->input('gender');
                $user->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d');
                $user->address = $request->input('address');
                $user->phone_no = $request->input('phone_no');

                if($request->input('profile_image') == "null") {
                    //do nothing since nothing to upload
                }
                else {
                     //delete current profile image
                     Storage::delete('public/profile_image/'.$user->profile_image);

                    $user->profile_image = $request->input("profile_image");
                }
                
                $user->save();

                //save volunteer profile data
                $volunteerProfile->emergency_contact = $request->input('emergency_contact');
                $volunteerProfile->emergency_name = $request->input('emergency_name');
                $volunteerProfile->emergency_relation = $request->input('emergency_relation');

                $volunteerProfile->save();

                $data = [
                    'status' => 'success',
                    'message' => 'Profile is updated.'
                ];
            }
            else {
                $data = [
                    'status' => 'fail',
                    'message' => 'Error in updating profile.'
                ];
            }
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //upload profile image
    public function uploadProfileImage(Request $request) {
        if($request->hasFile('profile_image')) {
             // Get filename withe the extention
             //$fileNameWithExt = $request->file('profile_image')->getClientOriginalName();

             // Uplaod image
            $path = $request->file('profile_image')->storeAs('public/profile_image', $request->input('file_name'));
        }
    }

    //reset password
    public function resetPassword(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            //check current password
            if(!Hash::check($request->input("current_password"), $user->password)) {
                $data = [
                    'status' => 'fail',
                    'message' => 'Current password is mismatched. If you forget the password, you may contact administrator to reset the password.'
                ];
            }
            else {
                $user->password = bcrypt($request->input('new_password'));
                $user->save();

                $data = [
                    'status' => 'success',
                    'message' => 'Password has been reset.'
                ];
            }
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //get today activities
    public function getTodayActivities(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $today = Carbon::today()->format('Y-m-d');
            $current_time = Carbon::now()->format('H:i:s');

            $todayActivities = Activity::where('activity_date', $today)->where('start_time', '>', $current_time)->where('status', 'A')->get(['activity_id', 'activity_title', 'activity_date', 'start_time', 'end_time', 'duration', 'slot', 'description', 'remark']);

            if($todayActivities) {
                foreach($todayActivities as $todayActivity) {
                    //format the time
                    $todayActivity->activity_date = Carbon::parse($todayActivity->activity_date)->format('d M Y');
                    $todayActivity->start_time = Carbon::parse($todayActivity->start_time)->format('h:i A');
                    $todayActivity->end_time = Carbon::parse($todayActivity->end_time)->format('h:i A');

                    //format description and remark
                    if($todayActivity->description == null) {
                        $todayActivity->description = "-";
                    }

                    if($todayActivity->remark == null) {
                        $todayActivity->remark = "-";
                    }

                    //check the participation status of each activities for the user
                    $participation = Participation::where('activity_id', $todayActivity->activity_id)->where('participant_id', $user->user_id)->get(['participation_id', 'status', 'invitation_code'])->first();

                    if($participation) {
                        $todayActivity->response = AppHelper::getParticipationResponse($participation->status);
                        $todayActivity->action = AppHelper::getParticipationAction($todayActivity->response);
                        $todayActivity->participation_id = $participation->participation_id;
                        $todayActivity->invitation_code = $participation->invitation_code;
                    }
                    else {
                        $todayActivity->response = "None";
                        $todayActivity->action = AppHelper::getParticipationAction($todayActivity->response);
                        $todayActivity->participation_id = "None";
                        $todayActivity->invitation_code = "None";
                    }

                    //get participation num
                    $participation_num = Participation::where('activity_id', $todayActivity->activity_id)->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J')->count();

                    $todayActivity->participation_num = $participation_num;

                    if($participation_num == $todayActivity->slot) {
                        $todayActivity->participation_status = "Full";

                        //cannot join if full
                        if($todayActivity->action == "Join") {
                            $todayActivity->action = "None";
                        }
                    }
                    else {
                        $todayActivity->participation_status = "Available";
                    }
                }
                
            }

            $data = [
                'status' => 'success',
                'data' => $todayActivities
            ];
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //get activities by date
    public function getActivitiesByDate(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $today = Carbon::today()->format('Y-m-d');
            $current_time = Carbon::now()->format('H:i:s');

            $todayActivities = Activity::where('activity_date', $today)->where('start_time', '>', $current_time)->where('status', 'A')->get(['activity_id', 'activity_title', 'activity_date', 'start_time', 'end_time', 'duration', 'slot', 'description', 'remark']);

            if($todayActivities) {
                foreach($todayActivities as $todayActivity) {
                    //format the time
                    $todayActivity->activity_date = Carbon::parse($todayActivity->activity_date)->format('d M Y');
                    $todayActivity->start_time = Carbon::parse($todayActivity->start_time)->format('h:i A');
                    $todayActivity->end_time = Carbon::parse($todayActivity->end_time)->format('h:i A');

                    //format description and remark
                    if($todayActivity->description == null) {
                        $todayActivity->description = "-";
                    }

                    if($todayActivity->remark == null) {
                        $todayActivity->remark = "-";
                    }

                    //check the participation status of each activities for the user
                    $participation = Participation::where('activity_id', $todayActivity->activity_id)->where('participant_id', $user->user_id)->get(['participation_id', 'status', 'invitation_code'])->first();

                    if($participation) {
                        $todayActivity->response = AppHelper::getParticipationResponse($participation->status);
                        $todayActivity->action = AppHelper::getParticipationAction($todayActivity->response);
                        $todayActivity->participation_id = $participation->participation_id;
                        $todayActivity->invitation_code = $participation->invitation_code;
                    }
                    else {
                        $todayActivity->response = "None";
                        $todayActivity->action = AppHelper::getParticipationAction($todayActivity->response);
                        $todayActivity->participation_id = "None";
                        $todayActivity->invitation_code = "None";
                    }

                    //get participation num
                    $participation_num = Participation::where('activity_id', $todayActivity->activity_id)->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J')->count();

                    $todayActivity->participation_num = $participation_num;

                    if($participation_num == $todayActivity->slot) {
                        $todayActivity->participation_status = "Full";

                        //cannot join if full
                        if($todayActivity->action == "Join") {
                            $todayActivity->action = "None";
                        }
                    }
                    else {
                        $todayActivity->participation_status = "Available";
                    }
                }
                
            }

            $data = [
                'status' => 'success',
                'data' => $todayActivities
            ];
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //join activity
    public function joinActivity(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();
        $activity = Activity::where('activity_id', $request->input('activity_id'))->get(['slot'])->first();

        if($user) {
            if($activity) {
                //get participation num
                $participation_num = Participation::where('activity_id', $request->input('activity-id'))->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J')->count();

                //slot is full
                if($participation_num == $activity->slot) {
                    $data = [
                        'status' => 'fail',
                        'message' => 'The slot for this activity is already full.'
                    ];
                }
                else { //the activity is available
                    if($request->input('participation_id') == "None") {
                        //create a new participation
                        $participation = new Participation;
                        $participation->participant_id = $user->user_id;
                        $participation->activity_id = $request->input('activity_id');
                        $participation->status = "J";
                        $participation->invitation_code = $user->user_id."INV_".time();
                        $participation->updated_by = $user->user_id;
                        $participation->save();
        
                        $data = [
                            'status' => 'success',
                            'message' => 'You have joined the selected activity.'
                        ];
                    }
                    else {
                        //update the participation
                        $participation = Participation::where('participation_id', $request->input('participation_id'))->get()->first();
        
                        if($participation) {
                            $participation->status = "J";
                            $participation->save();
        
                            $data = [
                                'status' => 'success',
                                'message' => 'You have joined the selected activity.'
                            ];
                        }
                        else {
                            $data = [
                                'status' => 'fail',
                                'message' => 'Unable to join the selected activity. Please try again later.'
                            ];
                        }
                    }
                }
            }
            else {
                $data = [
                    'status' => 'fail',
                    'message' => 'Unable to join the selected activity. Please try again later.'
                ];
            }
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }

    //withdraw activity
    public function withdrawActivity(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            //update participation
            $participation = Participation::where('participation_id', $request->input('participation_id'))->get()->first();

            if($participation) {
                $participation->status = "W";
                $participation->save();

                $data = [
                    'status' => 'success',
                    'message' => 'You have withdrawn from the selected activity.'
                ];
            }
            else {
                $data = [
                    'status' => 'fail',
                    'message' => 'Unable to withdraw from the activity. Please try again later.'
                ];
            }
            
        }
        else {
            $data = [
                'status' => 'invalid',
                'message' => 'Invalid session.'
            ];
        }

        return response()->json($data);
    }
}
