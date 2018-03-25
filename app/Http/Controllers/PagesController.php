<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserType;
use App\VolunteerProfile;
use App\Participation;
use App\Activity;
use App\OccupationType;
use App\MediumType;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
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

    //get the report
    public function report() {
        //get active staff number
        $staffNum = User::where('status', 'A')
                    ->where(function ($q) {
                        $q->where('usertype', 1)->orWhere('usertype', 2)->orWhere('usertype', 3);
                    })
                    ->count();

        //get active volunteer number (status A, total volunteer duration > 0)
        $activeVolunteerNum = DB::table('user')
                            ->join('volunteer_profile', 'user.user_id', '=', 'volunteer_profile.user_id')
                            ->where('user.usertype', 4)
                            ->where('user.status', 'A')
                            ->where('volunteer_profile.total_volunteer_duration', '>', 0)
                            ->count();

        //get new volunteer number (status A, total volunteer duration = 0)
        $newVolunteerNum = DB::table('user')
                            ->join('volunteer_profile', 'user.user_id', '=', 'volunteer_profile.user_id')
                            ->where('user.usertype', 4)
                            ->where('user.status', 'A')
                            ->where('volunteer_profile.total_volunteer_duration', 0)
                            ->count();

        //get ongoing activity (date >= today)
        $today = Carbon::today()->format('Y-m-d');
        $ongoingActivityNum = Activity::where('status', 'A')
                            ->where('activity_date', '>=', $today)
                            ->count();

        //get occupation
        $occupationTypes = OccupationType::get();

        $occupationResults = array();
        foreach($occupationTypes as $occupationType) {
            $occupationNum = VolunteerProfile::where('occupation', $occupationType->occupation_type_id)
                            ->count();
            
            $occupationResult = new OccupationType();
            $occupationResult->value = $occupationNum;
            $occupationResult->label = $occupationType->occupation_type_name;

            switch($occupationType->occupation_type_id) {
                case 1:
                    $occupationResult->color = '#ff0000';
                    $occupationResult->highlight = '#ff0000';
                    break;
                case 2:
                    $occupationResult->color = '#ff8c00';
                    $occupationResult->highlight = '#ff8c00';
                    break;
                case 3:
                    $occupationResult->color = '#8b0000';
                    $occupationResult->highlight = '#8b0000';
                    break;
                case 4:
                    $occupationResult->color = '#8a2be2';
                    $occupationResult->highlight = '#8a2be2';
                    break;
                case 5:
                    $occupationResult->color = '#32dc32';
                    $occupationResult->highlight = '#32dc32';
                    break;
                case 6:
                    $occupationResult->color = '#00ffff';
                    $occupationResult->highlight = '#00ffff';
                    break;
                case 7:
                    $occupationResult->color = '#008080';
                    $occupationResult->highlight = '#008080';
                    break;
                case 8:
                    $occupationResult->color = '#1e90ff';
                    $occupationResult->highlight = '#1e90ff';
                    break;
                case 9:
                    $occupationResult->color = '#f08080';
                    $occupationResult->highlight = '#f08080';
                    break;
                case 10:
                    $occupationResult->color = '#0000ff';
                    $occupationResult->highlight = '#0000ff';
                    break;
                case 11:
                    $occupationResult->color = '#808000';
                    $occupationResult->highlight = '#808000';
                    break;
                case 12:
                    $occupationResult->color = '#c0c0c0';
                    $occupationResult->highlight = '#c0c0c0';
                    break;
            }

            $occupationResults[] = $occupationResult;
                            
        }

        //get medium
        $mediumTypes = MediumType::get();

        $mediumResults = array();
        foreach($mediumTypes as $mediumType) {
            $mediumNum = VolunteerProfile::where('medium', $mediumType->medium_type_id)
                            ->count();
            
            $mediumResult = new MediumType();
            $mediumResult->value = $mediumNum;
            $mediumResult->label = $mediumType->medium_type_name;

            switch($mediumType->medium_type_id) {
                case 1:
                    $mediumResult->color = '#ff0000';
                    $mediumResult->highlight = '#ff0000';
                    break;
                case 2:
                    $mediumResult->color = '#ff8c00';
                    $mediumResult->highlight = '#ff8c00';
                    break;
                case 3:
                    $mediumResult->color = '#8a2be2';
                    $mediumResult->highlight = '#8a2be2';
                    break;
                case 4:
                    $mediumResult->color = '#32dc32';
                    $mediumResult->highlight = '#32dc32';
                    break;
                case 5:
                    $mediumResult->color = '#00ffff';
                    $mediumResult->highlight = '#00ffff';
                    break;
                case 6:
                    $mediumResult->color = '#1e90ff';
                    $mediumResult->highlight = '#1e90ff';
                    break;
                case 7:
                    $mediumResult->color = '#c0c0c0';
                    $mediumResult->highlight = '#c0c0c0';
                    break;
            }

            $mediumResults[] = $mediumResult;
                            
        }


        //get top 10 volunteers
        $volunteers = DB::table('user')
                        ->join('volunteer_profile', 'user.user_id', '=', 'volunteer_profile.user_id')
                        ->where('user.status', 'A')
                        ->where('user.usertype', 4)
                        ->select('user.full_name', 'user.ic_passport', 'volunteer_profile.total_volunteer_duration',
                        'volunteer_profile.updated_at')
                        ->orderBy('volunteer_profile.total_volunteer_duration', 'desc')
                        ->orderBy('volunteer_profile.updated_at', 'desc')
                        ->limit(10)
                        ->get();
        
        $topVolunteersName = array();
        $topVolunteersHour = array();

        foreach($volunteers as $volunteer) {
            // $topVolunteerName = [
            //     $volunteer->full_name
            // ];

            $topVolunteersName[] = $volunteer->full_name;

            // $topVolunteerHour = [
            //     $volunteer->total_volunteer_duration
            // ];

            $topVolunteersHours[] = $volunteer->total_volunteer_duration;
        }


        $data = [
            'staffNum' => $staffNum,
            'activeVolunteerNum' => $activeVolunteerNum,
            'newVolunteerNum' => $newVolunteerNum,
            'ongoingActivityNum' => $ongoingActivityNum,
            'occupations' => $occupationResults,
            'mediums' => $mediumResults,
            'volunteersName' => $topVolunteersName,
            'volunteersHour' => $topVolunteersHours
        ];

        //return $data;
        return view('pages.report')->with('data', $data);
    }
}
