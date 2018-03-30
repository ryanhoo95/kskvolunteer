<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon;
use Auth;
use App\Activity;
use App\ActivityType;
use App\User;
use Illuminate\Database\QueryException;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        

        try {
            $activities = Activity::where('status', 'A')->orderBy('activity_date', 'desc')->get();

            foreach($activities as $activity) {
                $created_by = $activity->created_by;

                $user = User::where('user_id', $created_by)->get(['profile_name'])->first();

                $activity->creator = $user->profile_name;
            }
        
            $data = [
                'activities' => $activities,
            ];

            return view('activity.index')->with('data', $data);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        // return $data;
    }

    /**
     * Display the selected activity.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $activity = Activity::findOrFail($id);

        $created_by = $activity->created_by;

        $user = User::where('user_id', $created_by)->get(['profile_name'])->first();

        $activity->creator = $user->profile_name;

        $data = [
            'activity' => $activity,
        ];

        return view('activity.show')->with('data', $data);
    }

    /**
     * Show the form for creating a new activity.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get the available activity type to be used as template
        $activity_types = ActivityType::where('status', 'A')->orderBy('activity_title', 'asc')->get(['activity_title', 'start_time', 'end_time', 'description', 'remark']);

        foreach($activity_types as $activity_type) {
            $activity_type->description = str_replace("\"", '\\"', $activity_type->description);
            $activity_type->remark = str_replace("\"", '\\"', $activity_type->remark);
        }

        $data = [
            'activity_types' => $activity_types
        ];

        return view('activity.create')->with('data', $data);
        //return $data;
    }

    /**
     * Store a newly created activity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //validation
        $rules = [
            'activity_title' => 'required',
            'slot' => 'required|numeric',
            'date' => 'required',
            'start_time' => 'required|date_format:h:i A',
            'end_time' => 'required|date_format:h:i A|after:start_time',
            'description' => 'nullable',
            'remark' => 'nullable',
        ];

        $messages = [
            'required' => 'Please fill out this field.',
            'slot.numeric' => 'The slot can only contain numbers.',
            'start_time.date_format' => 'Invalid time format.',
            'end_time.date_format' => 'Invalid time format.',
            'end_time.after' => 'End time must be greater than start time.'
        ];

        $request->validate($rules, $messages);

        //get the date range
        $start_date = Carbon::parse(substr($request->input('date'), 0, 11));
        $end_date = Carbon::parse(substr($request->input('date'), 14, 11));
        $dates = $this->dateRanges($start_date, $end_date);
        $start_time = Carbon::parse($request->input('start_time'));
        $end_time = Carbon::parse($request->input('end_time'));
        $duration = $end_time->diffInHours($start_time);
        

        // $data = [
        //     'start' => $start_date->format('d M Y'),
        //     'end' => $end_date->format('d M Y'),
        //     'dates' => $dates,
        //     'start time' => $start_time->format('h:i A'),
        //     'end_time' => $end_time->format('h:i A'),
        //     'duration' => $duration
        // ];

        foreach($dates as $date) {
            $description = str_replace("\r\n", '', $request->input('description'));
            $description = str_replace("\t", '', $description);
            $remark = str_replace("\r\n", '', $request->input('remark'));
            $remark = str_replace("\t", '', $remark);

            //create activity
            $activity = new Activity;
            $activity->activity_title = $request->input('activity_title');
            $activity->slot = $request->input('slot');
            $activity->activity_date = $date;
            $activity->start_time = Carbon::parse($request->input('start_time'))->format('H:i:s');
            $activity->end_time = Carbon::parse($request->input('end_time'))->format('H:i:s');
            $activity->duration = $duration;
            $activity->description = $description;
            $activity->remark = $remark;
            $activity->status = "A";
            $activity->created_by = Auth::user()->user_id;
            $activity->updated_by = Auth::user()->user_id;

            $activity->save();
        }

        return redirect('/activity')->with('success', 'Activity(s) is created.');

        // return $data;
        
    }

    private function dateRanges(Carbon $start, Carbon $end) {
        $dates = [];

        for($date = $start; $date->lte($end); $date->addDay()) {
            //format to mysql date
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    /**
     * Show the form for editing the activity.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);

        $data = [
            'activity' => $activity,
        ];

        return view('activity.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param string $action
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $action) {
        if($action == "update_info") {
            //validation
            $rules = [
                'activity_title' => 'required',
                'slot' => 'required|numeric',
                'date' => 'required|date',
                'start_time' => 'required|date_format:h:i A',
                'end_time' => 'required|date_format:h:i A|after:start_time',
                'description' => 'nullable',
                'remark' => 'nullable',
            ];

            $messages = [
                'required' => 'Please fill out this field.',
                'slot.numeric' => 'The slot can only contain numbers.',
                'date' => 'Invalid date format.',
                'start_time.date_format' => 'Invalid time format.',
                'end_time.date_format' => 'Invalid time format.',
                'end_time.after' => 'End time must be greater than start time.'
            ];

            $request->validate($rules, $messages);

            $start_time = Carbon::parse($request->input('start_time'));
            $end_time = Carbon::parse($request->input('end_time'));
            $duration = $end_time->diffInHours($start_time);

            $description = str_replace("\r\n", '', $request->input('description'));
            $description = str_replace("\t", '', $description);
            $remark = str_replace("\r\n", '', $request->input('remark'));
            $remark = str_replace("\t", '', $remark);

            $activity = Activity::find($id);
            $activity->activity_title = $request->input('activity_title');
            $activity->slot = $request->input('slot');
            $activity->activity_date = Carbon::parse($request->input('date'))->format('Y-m-d');
            $activity->start_time = Carbon::parse($request->input('start_time'))->format('H:i:s');
            $activity->end_time = Carbon::parse($request->input('end_time'))->format('H:i:s');
            $activity->duration = $duration;
            $activity->description = $description;
            $activity->remark = $remark;
            $activity->updated_by = Auth::user()->user_id;
            $activity->save();
            
            return redirect('/activity/'.$id)->with('success', 'Activity has been updated.');
        }
        else if($action == "cancel") {
            $activity = Activity::find($id);
            $activity->status = "I";
            $activity->updated_by = Auth::user()->user_id;
            $activity->save();

            return redirect('/activity')->with('success', 'Activity has been cancelled.');
        }
    }
}
