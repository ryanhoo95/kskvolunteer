<?php

namespace App\Http\Controllers;
use App\Participation;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    //

    /**
     * Display the participants for the selected activity.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showParticipation($id) {
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
        return view('pages.test')->with('data', $results);;
    }
}
