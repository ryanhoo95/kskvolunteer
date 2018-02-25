<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserType;
use Auth;

class PagesController extends Controller
{
    //go to homepage
    public function index() {
        if(Auth::user()) {
            $id = Auth::user()->usertype;
            $usertype = UserType::find($id);

            if($usertype->usertype_name == "Volunteer" || Auth::user()->status == "I") {
                return redirect('logout');
            }
            else {
                return view('pages.dashboard');
            }
        }
        else {
            return view('pages.dashboard');
        }
    }

    public function test($type, $id) {
        $user = User::find($id);

        $data = ['type' => $type, 'user' => $user];
        return view('pages.test')->with('data', $data);
    }
}
