<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\UserType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()) {
            // $id = Auth::user()->usertype;
            // $usertype = UserType::find($id);

            return view('profile.my_profile');
        }
        else {
            return redirect('/');
        }
        
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
            'date_of_birth' => 'required|date',
            'phone_no' => 'required|numeric',
            'address' => 'required',
        ];

        $messages = [
            'required' => 'Please fill out this field.',
            'profile_image.max' => 'Please make sure the size of the image is less than 2MB.',
            'phone_no.numeric' => 'The contact no. can only contain numbers.',
            'date' => 'Invalid date format.'
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

        //Update user
        $user = User::find($id);
        $user->profile_name = $request->input('profile_name');
        $user->full_name = $request->input('full_name');
        $user->gender = $request->input('gender');
        $user->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d');
        $user->phone_no = $request->input('phone_no');
        $user->address = $request->input('address');
        if($request->hasFile('profile_image')) {
            //delete previous image
            if($user->profile_image != 'no_image.png') {
                Storage::delete('public/profile_image/'.$user->profile_image);
            }

            //store the new image
            $user->profile_image = $fileNameToStore;
        }
        $user->save();

        return redirect('/profile')->with('success', 'Profile has been updated.');
    }

    /**
     * Show the form for reset password
     */
    public function resetPassword() {
        return view('/profile.reset_password');
    }

    /**
     * Update the passowrd.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id) {
        //check current password
        if(!Hash::check($request->input("current_password"), Auth::user()->password)) {
            return back()->with('error', 'Current password is mismatched.');
        }
        else {
            //validation for new password
            $rules = [
                'new_password' => 'required|min:8|regex:/^[A-Za-z0-9_@.\/#&+-]*$/|confirmed',
                'new_password_confirmation' => 'required|min:8',
            ];
    
            $messages = [
                'required' => 'Please fill out this field.',
                'confirmed' => 'New password is mismatched',
                'min' => 'Please make sure your password contains at least 8 characters.',
                'regex' => 'Password contains invalid character.'
            ];
    
            $request->validate($rules, $messages);

            //update password
            $user = User::find($id);
            $user->password = bcrypt($request->input('new_password'));

            $user->save();

            return redirect('/profile')->with('success', 'Password has been reset.');
        }
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
