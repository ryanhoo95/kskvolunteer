<?php

namespace App\Helpers;

use Auth;
use App\UserType;

class AppHelper
{
    //get the current user role
    public static function currentUserRole() {
        $usertype_id = Auth::user()->usertype;
        $usertype = UserType::find($usertype_id);

        return $usertype->usertype_name;
    }

    //get the user role
    public static function getUserRole($usertype_id) {
        $usertype = UserType::find($usertype_id);

        return $usertype->usertype_name;
    }
}