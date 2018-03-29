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

    //get the url to profile storage
    public static function getProfileStorageUrl() {
        return "http://192.168.43.139/storage/profile_image/";
    }

    //get participation response name
    public static function getParticipationResponse($status) {
        switch ($status) {
            case 'A':
                $response = "Absent";
                break;
            case 'P':
                $response = "Present";
                break;
            case 'J':
                $response = "Join";
                break;
            case 'W':
                $response = "Withdraw";
                break;
        }

        return $response;
    }

    //get allowed action based on participation response
    public static function getParticipationAction($response) {
        switch ($response) {
            case 'Absent':
            case 'Present':
                $action = "None";
                break;
            case 'Join':
                $action = "Withdraw";
                break;
            case 'None';
            case 'Withdraw';
                $action = "Join";
                break;
        }

        return $action;
    }

    //get attendance response name
    public static function getAttendanceResponse($status) {
        switch ($status) {
            case 'A':
                $response = "Absent";
                break;
            case 'P':
                $response = "Present";
                break;
            case 'J':
                $response = "Pending";
                break;
        }

        return $response;
    }

     //get allowed attendance action based on participation response
     public static function getAttendanceAction($response) {
        switch ($response) {
            case 'Absent':
            case 'Present':
                $action = "None";
                break;
            case 'Pending':
                $action = "Yes";
                break;
        }

        return $action;
    }
}