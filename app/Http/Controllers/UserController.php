<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserType;
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
        if(Auth::user()->user_id == $id) {
            return redirect('/profile');
        }
        else {

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
    public function store(Request $request, $type)
    {
        //validation
        $rules = [
            'full_name' => 'required',
            'email' => 'required|email|unique:user',
            'ic_passport' => 'required|alpha_num|unique:user|confirmed',
            'ic_passport_confirmation' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'phone_no' => 'required|numeric',
        ];

        $messages = [
            'required' => 'Please fill out this field.',
            'email' => 'Invalid email format.',
            'email.unique' => 'This email has already been taken.',
            'ic_passport.unique' => 'This IC / passport no. has already been taken.',
            'ic_passport.alpha_num' => 'IC / passport no. contains invalid character.', 
            'confirmed' => 'IC / passport no. is mismatched',
            'phone_no.numeric' => 'The contact no. can only contain numbers.',
        ];

        $request->validate($rules, $messages);

        //create user
        $user = new User;
        $user->full_name = $request->input('full_name');
        $user->profile_name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->ic_passport = $request->input('ic_passport');
        $user->password = bcrypt($request->input('ic_passport'));
        $user->gender = $request->input('gender');
        $user->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d');
        $user->phone_no = $request->input('phone_no');
        $user->address = "Kechara Soup Kitchen";
        $user->profile_image = "no_image.png";
        $user->status = "A";

        if(Auth::user()->usertype = 1) {
            $user->usertype = $request->input('usertype');
        }
        else {
            $user->usertype = "3";
        }

        $user->save();

        return redirect('/user/'.$type)->with('success', 'User Created');
        
    }
}
