<?php

namespace App\Http\Controllers;
use App\Participation;
use App\Activity;
use App\User;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if($request->input('date')) {
            //validation
            $rules = [
                'date' => 'required|date'
            ];

            $messages = [
                'required' => 'Please fill out this field.',
                'date' => 'Invalid date format.'
            ];

            $request->validate($rules, $messages);

            $date = Carbon::parse($request->input('date'))->format('Y-m-d');

            $activities = Activity::where('activity_date', $date)
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
        }
        else {
            $data = null;
        }
        

        return view('participation.index')->with('data', $data);
        //return $data;
    }

    /**
     * Display the participants for the selected activity.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //handle individual
        $individual = array();
        $invitationCodesSolo = Participation::where('activity_id', $id)
                                ->groupBy('invitation_code')
                                ->whereNotNull('invitation_code')
                                ->havingRaw('COUNT(invitation_code) = 1')
                                ->get(['invitation_code']);

        foreach($invitationCodesSolo as $invitationCodeSolo) {
            $participant = DB::table('participation')
                            ->join('user', 'participation.participant_id', '=', 'user.user_id')
                            ->join('volunteer_profile', 'user.user_id', '=', 'volunteer_profile.user_id')
                            ->where('participation.invitation_code', $invitationCodeSolo->invitation_code)
                            ->where(function ($q) {
                                $q->where('participation.status', 'A')
                                  ->orWhere('participation.status', 'P')
                                  ->orWhere('participation.status', 'J');
                            })
                            ->select('participation.participation_id', 'user.user_id', 'user.full_name',
                            'volunteer_profile.total_volunteer_duration', 'volunteer_profile.blacklisted_number',
                            'participation.status')
                            ->get()->first();

            if($participant) {
                if($participant->total_volunteer_duration == 0) {
                    $participant->category = 'Newbie';
                }
                else {
                    $participant->category = 'Regular';
                }
                                
                $individual[] = $participant;
            }
            
        }

        //handle vip
        $vips = Participation::whereNull('invitation_code')
                ->whereNotNull('participant_name')
                ->get(['participant_name', 'participant_remark']);

        //handle group
        $groups = array();
        $groupNumber = 1;
        $invitationCodesGroup = Participation::where('activity_id', $id)
                            ->groupBy('invitation_code')
                            ->whereNotNull('invitation_code')
                            ->havingRaw('COUNT(invitation_code) > 1')
                            ->get(['invitation_code']);

        foreach($invitationCodesGroup as $invitationCodeGroup) {
            $participants = DB::table('participation')
                        ->join('user', 'participation.participant_id', '=', 'user.user_id')
                        ->join('volunteer_profile', 'user.user_id', '=', 'volunteer_profile.user_id')
                        ->where('participation.invitation_code', $invitationCodeGroup->invitation_code)
                        ->where(function ($q) {
                            $q->where('participation.status', 'A')
                                ->orWhere('participation.status', 'P')
                                ->orWhere('participation.status', 'J');
                        })
                        ->select('participation.participation_id', 'user.user_id', 'user.full_name',
                        'volunteer_profile.total_volunteer_duration', 'volunteer_profile.blacklisted_number',
                        'participation.status')
                        ->get();
            
            if(count($participants) > 0) {
                foreach($participants as $participant) {
                    if($participant->total_volunteer_duration == 0) {
                        $participant->category = 'Newbie';
                    }
                    else {
                        $participant->category = 'Regular';
                    }
                }

                $group = [
                    'groupName' => 'Group '.$groupNumber,
                    'members' => $participants
                ];

                $groups[] = $group;
                $groupNumber++;
            }
        }

        $results = [
            'vips' => $vips,
            'individuals' => $individual,
            'groups' => $groups
        ];

        $data = [
            'message' => 'success',
            'data' => $results
        ];

        //return $results;
        return view('participation.show')->with('data', $results);;
    }
}
