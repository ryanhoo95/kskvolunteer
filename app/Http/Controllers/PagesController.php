<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserType;
use App\Participation;
use App\Activity;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Hash;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    //go to homepage
    public function index() {
        if(Auth::user()) {
            $id = Auth::user()->usertype;
            $usertype = UserType::find($id);

            if($usertype->usertype_name == "Volunteer") {
                Auth::logout();
                return redirect('login')->with('error', 'Unauthorized access.');
            }
            else if(Auth::user()->status == "I") {
                Auth::logout();
                return redirect('login')->with('error', 'Your account has been deactivated. Please contact ADMIN for assistance.');
            }
            else {
                $today = Carbon::today()->format('Y-m-d');

                $activities = Activity::where('activity_date', $today)
                        ->where('status', 'A')
                        ->orderBy('start_time', 'asc')
                        ->get(['activity_id', 'activity_title', 'start_time', 'end_time', 
                        'duration', 'slot', 'description', 'remark', 'activity_date']);

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


                    //get participation num
                    $participation_num = Participation::where('activity_id', $activity->activity_id)
                                        ->where(function ($q) {
                                            $q->where('status', 'A')->orWhere('status', 'P')->orWhere('status', 'J');
                                        })->count();

                    $activity->participation_num = $participation_num;

                    if($participation_num == $activity->slot) {
                        $activity->participation_status = "Full";
                    }
                    else {
                        $activity->participation_status = "Available";
                    }
                }

                $data = [
                    'activities' => $activities
                ];

                return view('pages.dashboard')->with('data', $data);
            }
        }
        else {
            return view('pages.dashboard');
        }
    }
}
