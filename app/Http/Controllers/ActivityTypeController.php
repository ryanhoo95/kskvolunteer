<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon;
use App\ActivityType;
use App\User;
use Auth;

class ActivityTypeController extends Controller
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
     * Display a listing of activity types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $activity_types = ActivityType::get();
        
        $data = [
            'activity_types' => $activity_types,
        ];

        return view('activity_type.index')->with('data', $data);
        //return $data;
    }

    /**
     * Display the selected activity type.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $activity_type = ActivityType::findOrFail($id);

        $data = [
            'activity_type' => $activity_type,
        ];

        return view('activity_type.show')->with('data', $data);
    }

    /**
     * Show the form for creating a new activity type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activity_type.create');
    }

    /**
     * Store a newly created activity type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //validation
        $rules = [
            'activity_type_name' => 'required',
            'activity_title' => 'required',
            'assembly_point' => 'required',
            'access' => 'required',
            'start_time' => 'required|date_format:h:i A',
            'end_time' => 'required|date_format:h:i A|after:start_time',
            'description' => 'nullable|max:1000',
            'remark' => 'nullable|max:1000',
        ];

        $messages = [
            'required' => 'Please fill out this field.',
            'date_format' => 'Invalid time format.',
            'end_time.after' => 'End time must be greater than start time.'
        ];

        $request->validate($rules, $messages);

        //create activity type
        $description = str_replace("\r\n", '', $request->input('description'));
        $description = str_replace("\t", '', $description);
        $remark = str_replace("\r\n", '', $request->input('remark'));
        $remark = str_replace("\t", '', $remark);

        $activity_type = new ActivityType;
        $activity_type->activity_type_name = $request->input('activity_type_name');
        $activity_type->activity_title = $request->input('activity_title');
        $activity_type->assembly_point = $request->input('assembly_point');
        $activity_type->access = $request->input('access');
        $activity_type->start_time = Carbon::parse($request->input('start_time'))->format('H:i:s');
        $activity_type->end_time = Carbon::parse($request->input('end_time'))->format('H:i:s');
        $activity_type->description = $description;
        $activity_type->remark = $remark;
        $activity_type->status = "A";
        $activity_type->created_by = Auth::user()->user_id;
        $activity_type->updated_by = Auth::user()->user_id;

        $activity_type->save();

        return redirect('/activity_type')->with('success', 'Template is created.');
        
    }

    /**
     * Show the form for editing the activity type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity_type = ActivityType::findOrFail($id);

        $data = [
            'activity_type' => $activity_type,
        ];

        return view('activity_type.edit')->with('data', $data);
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
                'activity_type_name' => 'required',
                'activity_title' => 'required',
                'assembly_point' => 'required',
                'access' => 'required',
                'start_time' => 'required|date_format:h:i A',
                'end_time' => 'required|date_format:h:i A|after:start_time',
                'description' => 'nullable|max:1000',
                'remark' => 'nullable|max:1000',
            ];

            $messages = [
                'required' => 'Please fill out this field.',
                'date_format' => 'Invalid time format.',
                'end_time.after' => 'End time must be greater than start time.'
            ];

            $request->validate($rules, $messages);

            $description = str_replace("\r\n", '', $request->input('description'));
            $description = str_replace("\t", '', $description);
            $remark = str_replace("\r\n", '', $request->input('remark'));
            $remark = str_replace("\t", '', $remark);

            $activity_type = ActivityType::find($id);
            $activity_type->activity_type_name = $request->input('activity_type_name');
            $activity_type->activity_title = $request->input('activity_title');
            $activity_type->assembly_point = $request->input('assembly_point');
            $activity_type->access = $request->input('access');
            $activity_type->start_time = Carbon::parse($request->input('start_time'))->format('H:i:s');
            $activity_type->end_time = Carbon::parse($request->input('end_time'))->format('H:i:s');
            $activity_type->description = $description;
            $activity_type->remark = $remark;
            $activity_type->updated_by = Auth::user()->user_id;
            $activity_type->save();
            
            return redirect('/activity_type/'.$id)->with('success', 'Template has been updated.');
        }
        else if($action == "activate") {
            $activity_type = ActivityType::find($id);
            $activity_type->status = "A";
            $activity_type->updated_by = Auth::user()->user_id;
            $activity_type->save();

            return redirect('/activity_type/'.$id)->with('success', 'Template has been activated.');
        }
        else if($action == "deactivate") {
            $activity_type = ActivityType::find($id);
            $activity_type->status = "I";
            $activity_type->updated_by = Auth::user()->user_id;
            $activity_type->save();

            return redirect('/activity_type/'.$id)->with('success', 'Template has been deactivated.');
        }
    }
}
