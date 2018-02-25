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
            $users = User::where('usertype', '<', 4)->get();
        }
        else if($type == "volunteer") {
            $users = User::where('usertype', 4)->get();
        }

        $data = [
            'type' => $type,
            'users' => $users
        ];

        return view('user.index')->with('data', $data);
    }

    /**
     * Display the selected user.
     *
     * @param string $type
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($type, $id) {

    }

    /**
     * Show the form for creating a new user.
     *
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        if(Auth::user()->usertype == 1) {
            $usertypes_to_add = array(2, 3);
        }
        else {
            $usertypes_to_add = array(3);
        }

        return view('user.create')->with('usertypes_to_add');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
}
