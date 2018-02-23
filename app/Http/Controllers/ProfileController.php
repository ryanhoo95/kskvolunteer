<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use \Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.my_profile');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('profile.my_profile_edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validation
        $rules = [
            'profile_image' => 'image|nullable|max:1999',
            'profile_name' => 'required',
            'full_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
        ];

        $messages = [
            'required' => 'Please fill out this field.',
            'profile_image.max' => 'Please make sure the size of the image is less than 2MB.'
        ];

        $request->validate($rules, $messages);

        // Handle File Upload
        if($request->hasFile('profile_image')) {
            // Get filename withe the extention
            $fileNameWithExt = $request->file('profile_image')->getClientOriginalName();
            // Get just filename
            $filename = pathInfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Uplaod image
            $path = $request->file('profile_image')->storeAs('public/profile_image', $fileNameToStore);
        }

        // Create Post
        $user = User::find($id);
        $user->profile_name = $request->input('profile_name');
        $user->full_name = $request->input('full_name');
        $user->gender = $request->input('gender');
        $user->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d');
        $user->phone_no = $request->input('phone_no');
        $user->address = $request->input('address');
        if($request->hasFile('profile_image')) {
            $user->profile_image = $fileNameToStore;
        }
        $user->save();


        return redirect('/profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
