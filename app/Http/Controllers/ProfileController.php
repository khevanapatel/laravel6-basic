<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(){
    	$users = User::where('email', Auth::user()->email)->first();
    	return view('profile',compact('users'));
    }

    public function update(Request $request){
    	Validator::make($request->all(), [
            'name' => 'required|string|min:3',
        ])->validate();
    	$user = User::find(Auth::user()->id);
        $user->name = $request->name;
        if($request->hasFile('profile_pic')){
            $request->validate([
                'profile_pic' => 'image|mimes:jpeg,png,gif,svg,jpg|max:1024'
            ]);
            $imgName = time().'.'.$request->profile_pic->getClientOriginalExtension();
            $request->profile_pic->move(public_path('images/profiles'), $imgName);
            $user->profile_pic = $imgName;
            if(Auth::user()->profile_pic !== null && file_exists(public_path('images/profiles')."/".Auth::user()->profile_pic)){
            	unlink(public_path('images/profiles')."/".Auth::user()->profile_pic);
            }
        }
        $user->save();
        return redirect()->back()->with('success','Your profile is updated successfully.');
    }

    public function changePassword(Request $request){
    	Validator::make($request->all(), [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8|same:password_confirmation',
        ])->validate();
        if(Hash::check($request->old_password,Auth::user()->password)){
        	$user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->back()->with('success','Your password is changed successfully.');
        }
        else{
            return redirect()->back()->with('error','Oops! You entered wrong current password.');

        }

    }
} 
