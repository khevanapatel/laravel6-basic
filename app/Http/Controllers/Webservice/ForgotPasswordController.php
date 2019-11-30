<?php

namespace App\Http\Controllers\Webservice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use Session;
use Hash;
use Mail;
use Exception;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $email = str_replace(' ','', $request->email);
        $user = User::where('email', $email)->first();

        if($user != null){
            $otp = mt_rand(100000, 999999);
            Session::put('otp', strtotime(date('Y-m-d H:i:s')));
            $session_otp = Session::get('otp');
            $user_pwd = User::find($user->id);
            $user_pwd->otp = $otp;
            $user_pwd->session_otp = $session_otp;
            $user_pwd->save(); 

            $this->sendEmail($user,$otp);
            if(count(Mail::failures()) > 0){
                return response()->json(['Message' => 'Failed to send password reset email, please try again.']); 
            }
            return response()->json(['Message' => 'One Time Password (OTP) has been sent to your registered email id, Please Enter OTP here to reset your password.']);  
        }
        else{
            return response()->json(['Error' => 'This email not valid, Please enter register email.']); 
        }
    }

    public function sendEmail($user,$otp)
    {
        Mail::send('emails.forgotPassword',['user'=>$user,'otp'=>$otp],
            function($message)use($user)
            { 
              $message->to($user->email);
              $message->subject("Hello $user->name, forgot Password for Account!");
            }
        );
    }

    public function otpVerify(Request $request)
    {
        if(!Auth::check())    
        {
            $email = str_replace(' ','', $request->email);
            $otp = str_replace(' ','', $request->otp);
            $user_id=User::where('email', $email)->select('id')->first();
            if($user_id != Null)
            {   
                $id = $user_id->id;
                $user = User::find($id);
                $nowtime = strtotime(date('Y-m-d H:i:s'));
                $otptimeout = $user->session_otp+60;
                if($nowtime < $otptimeout)  
                {   
                    if ($user->otp  == $request->otp)
                    {   
                        return response()->json(['Message' => 'Welcome :- '.$user->email . ' verified, Please set/change your password here.']); 
                    }else{
                        return response()->json(['Error' => 'Invalid OTP. Enter Correct OTP Number.']); 
                    }
                }else
                {
                    return response()->json(['Message' => 'OTP Session is expired.']); 
                } 
            }
        }
    }

    public function reset(Request $request)
    {
          $user = User::where('email',$request->email)->first();

          if($user != null){
          $validator = Validator::make($request->all(), [ 
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'string', 'min:8', 'same:password']
            ]);
            
          if($request->password === $request->password_confirmation){
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['Message' => 'Your new password has been saved. Please use new password to login.']); 
          }
          else{
            return response()->json(['Error' => 'Your Password confirmation does not match.']); 
          }
 
        }else{
            return response()->json(['Error' => 'This email not valid, Please enter register email']); 
        }
    }
}