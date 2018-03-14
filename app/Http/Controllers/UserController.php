<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserType;
use App\VolunteerProfile;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function index($type) {
        $users = null;

        if($type == "staff") {
            $users = User::where('usertype', '<', 4)->orderBy('user_id', 'asc')->get();
        }
        else if($type == "volunteer") {
            $users = User::where('usertype', 4)->orderBy('user_id', 'asc')->get();
        }

        $data = [
            'type' => $type,
            'users' => $users
        ];

        return view('user.index')->with('data', $data);
        //return $data;
    }

    /**
     * Display the selected user.
     *
     * @param string $type
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($type, $id) {
        //go to my profile if this the user click on his own name

        if(Auth::user()) {
            if(Auth::user()->user_id == $id) {
                return redirect('/profile');
            }
            else {
                $user = User::find($id);
                $volunteer_profile = VolunteerProfile::where('user_id', $id)->get()->first();
                // $usertype = UserType::find($user->usertype);
    
                $data = [
                    'user' => $user,
                    'volunteer_profile' => $volunteer_profile,
                    'type' => $type
                ];
    
                return view('user.show')->with('data', $data);
            }
        }
        else {
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        return view('user.create')->with('type', $type);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type) {
        //validation
        $rules = [
            'full_name' => 'required',
            'email' => 'required|email|unique:user|confirmed',
            'email_confirmation' => 'required|email',
            'ic_passport' => 'required|alpha_num|unique:user|confirmed',
            'ic_passport_confirmation' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'phone_no' => 'required|numeric',
        ];

        $messages = [
            'required' => 'Please fill out this field.',
            'email' => 'Invalid email format.',
            'email.confirmed' => 'Email mismatched.',
            'email.unique' => 'This email has already been taken.',
            'ic_passport.unique' => 'This IC / passport no. has already been taken.',
            'ic_passport.alpha_num' => 'IC / passport no. contains invalid character.', 
            'ic_passwport.confirmed' => 'IC / passport no. is mismatched.',
            'phone_no.numeric' => 'The contact no. can only contain numbers.',
            'date' => 'Invalid date format.'
        ];

        $request->validate($rules, $messages);

        //format ic passport
        $ic_passport = $request->input('ic_passport');
        $ic_passport_replace = str_replace('-', '', $ic_passport);
        $ic_passport_formmated = strtoupper($ic_passport_replace);

        //create user
        $user = new User;
        $user->full_name = $request->input('full_name');
        $user->profile_name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->ic_passport = $ic_passport_formmated;
        $user->password = bcrypt($request->input($ic_passport_formmated));
        $user->gender = $request->input('gender');
        $user->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d');
        $user->phone_no = $request->input('phone_no');
        $user->address = "Kechara Soup Kitchen";
        $user->profile_image = "no_image.png";
        $user->status = "A";

        if(Auth::user()->usertype == 1) {
            $user->usertype = $request->input('usertype');
        }
        else {
            $user->usertype = "3";
        }

        $user->save();

        return redirect('/user/'.$type)->with('success', 'User is created.');
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $type
     * @param  int  $id
     * @param string $action
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $type, $id, $action) {
        if($action == "reset_password") {
            $user = User::find($id);
            $user->password = bcrypt($user->ic_passport);
            $user->save();
            
            return redirect('/user/'.$type.'/'.$id.'/profile')->with('success', 'Password has been reset to IC / passport no.');
        }
        else if($action == "activate") {
            $user = User::find($id);
            $user->status = "A";
            $user->save();

            return redirect('/user/'.$type.'/'.$id.'/profile')->with('success', 'User has been activated.');
        }
        else if($action == "deactivate") {
            $user = User::find($id);
            $user->status = "I";
            $user->api_token = null;
            $user->save();

            return redirect('/user/'.$type.'/'.$id.'/profile')->with('success', 'User has been deactivated.');
        }
        else if($action == "promote_to_staff") {
            $user = User::find($id);
            $user->usertype = "3";
            $user->save();

            return redirect('/user/staff/'.$id.'/profile')->with('success', 'User has been promoted as Staff.');
        }
        else if($action == "promote_to_admin") {
            $user = User::find($id);
            $user->usertype = "2";
            $user->save();

            return redirect('/user/'.$type.'/'.$id.'/profile')->with('success', 'User has been promoted as Admin.');
        }
        else if($action == "demote_to_staff") {
            $user = User::find($id);
            $user->usertype = "3";
            $user->save();

            return redirect('/user/'.$type.'/'.$id.'/profile')->with('success', 'User has been demoted as Staff.');
        }

    }
}
