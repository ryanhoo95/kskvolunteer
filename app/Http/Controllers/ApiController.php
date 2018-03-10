<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\User;
use App\UserType;
use App\VolunteerProfile;
use Carbon\Carbon;
use App\Activity;
use App\ActivityType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
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

                $user->image_url = "http://192.168.43.139/storage/profile_image/".$user->profile_image;

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
        $user = User::where('api_token', $request->input('api_token'))->get()->first();

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

    //register
    public function register(Request $request) {
        //check for unique email
        $userByEmail = User::where('email', $request->input('email'))->get()->first();

        if($userByEmail) {
            $data = [
                'status' => 'fail',
                'message' => 'This email is already taken. Please use another email.'
            ];

            return response()->json($data);
        }

        //check for unique ic passport
        $userByIc = User::where('ic_passport', $request->input('ic_passport'))->get()->first();

        if($userByIc) {
            $data = [
                'status' => 'fail',
                'message' => 'This IC or passport no. is already taken. Please contact administrator for assistance.'
            ];

            return response()->json($data);
        }

        //register the user if checking passed
        $user = new User;
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->full_name = $request->input('full_name');
        $user->profile_name = $request->input('profile_name');
        $user->gender = $request->input('gender');
        $user->ic_passport = $request->input('ic_passport');
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
                $profile->profile_image = "http://192.168.43.139/storage/profile_image/".$user->profile_image;
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
}
