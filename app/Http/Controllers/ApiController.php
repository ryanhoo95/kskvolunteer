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
use Illuminate\Support\Facades\DB;

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
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id', 'profile_image'])->first();

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
                    //delete the image if it is not default image
                    if($user->profile_image != "no_image.png") {
                        Storage::delete('public/profile_image/'.$user->profile_image);
                     }

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

            $todayActivities = Activity::where('activity_date', $today)->where('start_time', '>', $current_time)->where('status', 'A')->orderBy('start_time', 'asc')->get(['activity_id', 'activity_title', 'activity_date', 'start_time', 'end_time', 'duration', 'slot', 'description', 'remark']);

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
                    $participation_num = Participation::where('activity_id', $todayActivity->activity_id)->where(function ($q) {
                        $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                    })->count();

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
            $date = Carbon::parse($request->input('date'))->format('Y-m-d');
            $today = Carbon::today()->format('Y-m-d');
            $current_time = Carbon::now()->format('H:i:s');

            //if selected date is today, get only the onwards available activities
            if($date == $today) {
                $activities = Activity::where('activity_date', $date)->where('start_time', '>', $current_time)->where('status', 'A')->orderBy('start_time', 'asc')->get(['activity_id', 'activity_title', 'activity_date', 'start_time', 'end_time', 'duration', 'slot', 'description', 'remark']);
            }
            else {
                $activities = Activity::where('activity_date', $date)->where('status', 'A')->orderBy('start_time', 'asc')->get(['activity_id', 'activity_title', 'activity_date', 'start_time', 'end_time', 'duration', 'slot', 'description', 'remark']);
            }
           

            if($activities) {
                foreach($activities as $activity) {
                    //format the time
                    $activity->activity_date = Carbon::parse($activity->activity_date)->format('d M Y');
                    $activity->start_time = Carbon::parse($activity->start_time)->format('h:i A');
                    $activity->end_time = Carbon::parse($activity->end_time)->format('h:i A');

                    //format description and remark
                    if($activity->description == null) {
                        $activity->description = "-";
                    }

                    if($activity->remark == null) {
                        $activity->remark = "-";
                    }

                    //check the participation status of each activities for the user
                    $participation = Participation::where('activity_id', $activity->activity_id)->where('participant_id', $user->user_id)->get(['participation_id', 'status', 'invitation_code'])->first();

                    if($participation) {
                        $activity->response = AppHelper::getParticipationResponse($participation->status);
                        $activity->action = AppHelper::getParticipationAction($activity->response);
                        $activity->participation_id = $participation->participation_id;
                        $activity->invitation_code = $participation->invitation_code;
                    }
                    else {
                        $activity->response = "None";
                        $activity->action = AppHelper::getParticipationAction($activity->response);
                        $activity->participation_id = "None";
                        $activity->invitation_code = "None";
                    }

                    //get participation num
                    $participation_num = Participation::where('activity_id', $activity->activity_id)->where(function ($q) {
                        $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                    })->count();

                    $activity->participation_num = $participation_num;

                    if($participation_num == $activity->slot) {
                        $activity->participation_status = "Full";

                        //cannot join if full
                        if($activity->action == "Join") {
                            $activity->action = "None";
                        }
                    }
                    else {
                        $activity->participation_status = "Available";
                    }
                }
                
            }

            $data = [
                'status' => 'success',
                'data' => $activities
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

        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $start_time = Carbon::parse($request->input('start_time'))->format('H:i:s');
        $end_time = Carbon::parse($request->input('end_time'))->format('H:i:s');
        // $start = Carbon::parse($request->input('date')." ".$request->input('start_time'));
        // $end = Carbon::parse($request->input('date')." ".$request->input('end_time'));

        //used to check for clashing
        $activities = Activity::where('activity_date', $date)->where(function ($p) use ($start_time, $end_time) {
            $p->where(function ($query) use ($start_time, $end_time) {
                $query->where('start_time', '>=', $start_time)->where('start_time', '<', $end_time);
            })
            ->orWhere(function ($query) use ($start_time, $end_time) {
                $query->where('end_time', '>=', $start_time)->where('end_time', '<', $end_time);
            })
            ->orWhere(function ($query) use ($start_time, $end_time) {
                $query->where('start_time', $start_time)->where('end_time', $end_time);
            });
        })->get(['activity_id']);


        $clash = false;

        if($user) {
            foreach($activities as $activityClash) {
                //get the participation
                $participationClash = Participation::where('activity_id', $activityClash->activity_id)->where('participant_id', $user->user_id)->where('status', 'J')->get()->first();

                if($participationClash) {
                    $clash = true;
                    break;
                }
            }

            if($clash) {
                $data = [
                    'status' => 'fail',
                    'message' => 'You have another participation which clash with the selected activity.',
                    'data' => $participationClash
                ];
            }
            else {
                $activity = Activity::where('activity_id', $request->input('activity_id'))->where('status', 'A')->get(['slot'])->first();

                if($activity) {
                    //get participation num
                    $participation_num = Participation::where('activity_id', $request->input('activity_id'))->where(function ($q) {
                        $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                    })->count();
    
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
                                $participation->invitation_code = $user->user_id."INV_".time();
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

     //get active participation
    public function getActiveParticipations(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $today = Carbon::today()->format('Y-m-d');
            $current_time = Carbon::now()->format('H:i:s');

            $participations = DB::table('participation')
                            ->join('activity', 'participation.activity_id', '=', 'activity.activity_id')
                            ->where('activity.status', 'A')
                            ->where('participation.participant_id', $user->user_id)
                            ->where('participation.status', 'J')
                            ->select('participation.participation_id', 'participation.activity_id', 'participation.invitation_code',
                            'activity.activity_title', 'activity.start_time', 'activity.end_time', 'activity.slot', 'activity.description',
                            'activity.remark', 'activity.activity_date')
                            ->orderBy('activity.activity_date', 'asc')
                            ->orderBy('activity.start_time', 'asc')
                            ->get();

            if($participations) {
                foreach($participations as $participation) {

                    //check whetehr activity already started
                    if(($participation->activity_date == $today && $participation->start_time < $current_time) 
                        || $participation->activity_date < $today) {
                        $participation->action = "None";
                    }
                    else {
                        $participation->action = "Withdraw";
                    }

                    //format description and remark
                    if($participation->description == null) {
                        $participation->description = "-";
                    }

                    if($participation->remark == null) {
                        $participation->remark = "-";
                    }

                    $participation->activity_date = Carbon::parse($participation->activity_date)->format('d M Y');
                    $participation->start_time = Carbon::parse($participation->start_time)->format('h:i A');
                    $participation->end_time = Carbon::parse($participation->end_time)->format('h:i A');
                    $participation->response = "Join";

                    //get participation num
                    $participation_num = Participation::where('activity_id', $participation->activity_id)->where(function ($q) {
                        $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                    })->count();

                    $participation->participation_num = $participation_num;

                    if($participation_num == $participation->slot) {
                        $participation->participation_status = "Full";
                    }
                    else {
                        $participation->participation_status = "Available";
                    }

                }
            }

            $data = [
                'status' => 'success',
                'data' => $participations
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

    //get active participation 2
    public function getActiveParticipations2(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $today = Carbon::today()->format('Y-m-d');
            $current_time = Carbon::now()->format('H:i:s');

            $participations = Participation::where('participant_id', $user->user_id)->where('status', 'J')->get(['participation_id', 'activity_id', 'invitation_code']);

            if($participations) {
                foreach($participations as $participation) {
                    $activity = Activity::where('activity_id', $participation->activity_id)->where('status', 'A')->get(['activity_title', 'activity_date', 'start_time', 'end_time', 'slot', 'description', 'remark'])->first();

                    if($activity) {
                        $participation->display = "flex";
                        $participation->action = "Withdraw";

                        //check whetehr activity already started
                        if($activity->activity_date == $today && $activity->start_time < $current_time) {
                            $participation->action = "None";
                        }
                        else {
                            $participation->action = "Withdraw";
                        }

                        //format description and remark
                        if($activity->description == null) {
                            $participation->description = "-";
                        }
                        else {
                            $participation->description = $activity->description;
                        }

                        if($activity->remark == null) {
                            $participation->remark = "-";
                        }
                        else {
                            $participation->remark = $activity->remark;
                        }

                        $participation->activity_title = $activity->activity_title;
                        $participation->activity_date = Carbon::parse($activity->activity_date)->format('d M Y');
                        $participation->start_time = Carbon::parse($activity->start_time)->format('h:i A');
                        $participation->end_time = Carbon::parse($activity->end_time)->format('h:i A');
                        $participation->slot = $activity->slot;

                        //get participation num
                        $participation_num = Participation::where('activity_id', $participation->activity_id)->where(function ($q) {
                            $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                        })->count();

                        $participation->participation_num = $participation_num;

                        if($participation_num == $activity->slot) {
                            $participation->participation_status = "Full";
                        }
                        else {
                            $participation->participation_status = "Available";
                        }


                    }
                    else {
                        //no need to display inactive activity
                        $participation->display = "none";
                    }
                }
            }

            $data = [
                'status' => 'success',
                'data' => $participations
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

    //get invited participants, where they already accept the invitation
    public function getInvitedParticipants(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            //get participants of the same invitation code
            $participants = DB::table('participation')
            ->join('user', 'participation.participant_id', '=', 'user.user_id')
            ->where('participation.activity_id', $request->input('activity_id'))
            ->where('participation.participant_id', '!=', $user->user_id)
            ->where('participation.invitation_code', $request->input('invitation_code'))
            ->where(function ($q) {
                $q->where('participation.status', 'A')
                  ->orWhere('participation.status', 'P')
                  ->orWhere('participation.status', 'J');
            })
            ->select('user.user_id', 'user.full_name', 'user.profile_name', 'user.profile_image')
            ->get();

            foreach($participants as $participant) {
                $participant->profile_image =  AppHelper::getProfileStorageUrl().$participant->profile_image;
            }

            $data = [
                'status' => 'success',
                'data' => $participants
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

    //get volunteers for invite
    public function getVolunteersForInvite(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $volunteers = User::where('status', 'A')
                        ->where('usertype', '4')
                        ->where('user_id', '!=', $user->user_id)
                        ->where(function ($q) use ($request){
                            $q->where('profile_name', 'like', '%'.$request->input('name').'%')
                              ->orWhere('full_name', 'like', '%'.$request->input('name').'%');
                        })
                        ->get(['user_id', 'full_name', 'profile_name', 'profile_image']);

            foreach($volunteers as $volunteer) {
                $volunteer->profile_image = AppHelper::getProfileStorageUrl().$volunteer->profile_image;

                //check whether this volunteer already join the activity
                $participation = Participation::where('activity_id', $request->input('activity_id'))
                                ->where('participant_id', $volunteer->user_id)
                                ->where('status', 'J')
                                ->get()->first();
                
                if($participation) {
                    $volunteer->invite_status = "Joined";
                }
                else {
                    //check whether this volunteer is invited by the same group, and is pending
                    $invitation = Invitation::where('activity_id', $request->input('activity_id'))
                                ->where('status', 'P')
                                ->where('target_to', $volunteer->user_id)
                                ->where('invitation_code', $request->input('invitation_code'))
                                ->get()->first();

                    if($invitation) {
                        $volunteer->invite_status = "Invited";
                    }
                    else {
                        $volunteer->invite_status = "None";
                    }
                }

            }

            $data = [
                'status' => 'success',
                'data' => $volunteers
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

    //send invitation
    public function sendInvitation(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            //check whether this volunteer already join the activity
            $participation = Participation::where('activity_id', $request->input('activity_id'))
            ->where('participant_id', $request->input('target_to'))
            ->where('status', 'J')
            ->get()->first();

            if($participation) {
                $data = [
                    'status' => 'fail',
                    'message' => $request->input('full_name')." had joined this activity."
                ];
            }
            else {
                //check whether this volunteer is invited by the same group, and is pending
                $invitation = Invitation::where('activity_id', $request->input('activity_id'))
                            ->where('status', 'P')
                            ->where('target_to', $request->input('target_to'))
                            ->where('invitation_code', $request->input('invitation_code'))
                            ->get()->first();

                if($invitation) {
                    $data = [
                        'status' => 'fail',
                        'message' => $request->input('full_name').' had been invited.'
                    ];
                }
                else {
                    //send invitation
                    $sendInvitation = new Invitation;
                    $sendInvitation->activity_id = $request->input('activity_id');
                    $sendInvitation->invited_by = $user->user_id;
                    $sendInvitation->target_to = $request->input('target_to');
                    $sendInvitation->invitation_code = $request->input('invitation_code');
                    $sendInvitation->status = 'P';
                    $sendInvitation->save();

                    $data = [
                        'status' => 'success',
                        'message' => 'Invitation sent to '.$request->input('full_name').'.'
                    ];
                }
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

    //get pending invitations
    public function getPendingInvitations(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $invitations = DB::table('invitation')
                            ->join('activity', 'invitation.activity_id', '=', 'activity.activity_id')
                            ->join('user', 'invitation.invited_by', '=', 'user.user_id')
                            ->where('invitation.status', 'P')
                            ->where('invitation.target_to', $user->user_id)
                            ->where('activity.status', 'A')
                            ->select('invitation.invitation_id', 'invitation.activity_id', 'invitation.invited_by',
                            'invitation.invitation_code', 'activity.activity_title', 'activity.activity_date', 
                            'activity.start_time', 'activity.end_time', 'activity.slot', 'activity.description', 
                            'activity.remark', 'user.full_name', 'user.profile_name', 'user.profile_image')
                            ->get();

            foreach($invitations as $invitation) {
                //format date time and text
                $invitation->activity_date = Carbon::parse($invitation->activity_date)->format('d M Y');
                $invitation->start_time = Carbon::parse($invitation->start_time)->format('h:i A');
                $invitation->end_time = Carbon::parse($invitation->end_time)->format('h:i A');

                if($invitation->description == null) {
                    $invitation->description = '-';
                }

                if($invitation->remark == null) {
                    $invitation->remark = '-';
                }

                //format profile image
                $invitation->profile_image = AppHelper::getProfileStorageUrl().$invitation->profile_image;

                //get participation num
                $participation_num = Participation::where('activity_id', $invitation->activity_id)->where(function ($q) {
                    $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                })->count();

                $invitation->participation_num = $participation_num;

                if($participation_num == $invitation->slot) {
                    $invitation->participation_status = "Full";
                }
                else {
                    $invitation->participation_status = "Available";
                }
            }

            $data = [
                'status' => 'success',
                'data' => $invitations
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

    //reject invitation
    public function rejectInvitation(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        if($user) {
            $invitation = Invitation::where('invitation_id', $request->input('invitation_id'))->get()->first();

            if($invitation) {
                $invitation->status = 'R';
                $invitation->save();

                $data = [
                    'status' => 'success',
                    'message' => 'The invitation is rejected.'
                ];
            }
            else {
                $data = [
                    'status' => 'fail',
                    'message' => 'Unable to reject the invitation. Please try again later.'
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

    //accept invitation
    public function acceptInvitation(Request $request) {
        $user = User::where('api_token', $request->input('api_token'))->get(['user_id'])->first();

        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $start_time = Carbon::parse($request->input('start_time'))->format('H:i:s');
        $end_time = Carbon::parse($request->input('end_time'))->format('H:i:s');

        if($user) {
            $invitation = Invitation::where('invitation_id', $request->input('invitation_id'))->get()->first();

            if($invitation) {
                //used to check for clashing
                $activities = Activity::where('activity_date', $date)->where(function ($p) use ($start_time, $end_time) {
                    $p->where(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', '>=', $start_time)->where('start_time', '<', $end_time);
                    })
                    ->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('end_time', '>=', $start_time)->where('end_time', '<', $end_time);
                    })
                    ->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', $start_time)->where('end_time', $end_time);
                    });
                })->get(['activity_id']);


                $clash = false;

                foreach($activities as $activityClash) {
                    //get the participation
                    $participationClash = Participation::where('activity_id', $activityClash->activity_id)->where('participant_id', $user->user_id)->where('status', 'J')->get()->first();
    
                    if($participationClash) {
                        $clash = true;
                        break;
                    }
                }
    
                if($clash) {
                    $data = [
                        'status' => 'fail',
                        'message' => 'You have another participation which clash with this invitation.',
                    ];
                }
                else {
                    $activity = Activity::where('activity_id', $request->input('activity_id'))->where('status', 'A')->get()->first();
    
                    if($activity) {
                        //get participation num
                        $participation_num = Participation::where('activity_id', $request->input('activity_id'))->where(function ($q) {
                            $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                        })->count();
        
                        //slot is full
                        if($participation_num == $activity->slot) {
                            $data = [
                                'status' => 'fail',
                                'message' => 'The slot for this activity is already full.'
                            ];
                        }
                        else { //the activity is available
                            //check whether got participation of this activity
                            $hasParticipation = Participation::where('activity_id', $request->input('activity_id'))
                                                ->where('participant_id', $user->user_id)->get()->first();

                            if($hasParticipation) {
                                $hasParticipation->status = 'J';
                                $hasParticipation->invitation_code = $request->input('invitation_code');
                                $hasParticipation->save();
                            }
                            else {
                                //create a new participation
                                $participation = new Participation;
                                $participation->participant_id = $user->user_id;
                                $participation->activity_id = $request->input('activity_id');
                                $participation->status = "J";
                                $participation->invitation_code = $request->input('invitation_code');
                                $participation->updated_by = $user->user_id;
                                $participation->save();
                            }
                            
                            $invitation->status = 'A';
                            $invitation->save();

                            $data = [
                                'status' => 'success',
                                'message' => 'The invitation is accepted.'
                            ];
                            
                        }
                    }
                    else {
                        $data = [
                            'status' => 'fail',
                            'message' => 'Unable to accept the invitation. Please try again later.'
                        ];
                    }
                }
            }
            else {
                $data = [
                    'status' => 'fail',
                    'message' => 'Unable to accept the invitation. Please try again later.'
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
