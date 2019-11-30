<?php

namespace App\Http\Controllers\Webservice;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Notifications\registerUser;
// use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Validator;
use Mail;
use Str;
use Session;

class RegistrationController extends Controller
{
   /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

      // ....Validation....
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

     //....Registration(Data Store In User Table)....
    protected function create(array $data)
    {
        $otp = mt_rand(100000,999999);
        Session::put('otp', strtotime(date('Y-m-d H:i:s')));
        $sessionOtp = Session::get('otp');
       
        return $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => '2',
                'api_token' => Str::random(60),
                'otp' => $otp,
                'session_otp' => $sessionOtp,
            ]);
    }

    protected function registered(Request $request, $user)
    {
        Mail::send('emails.apiVerifiedPassword',['user'=>$user, 'otp'=>$user->otp],
            function($message)use($user)
            {
                $message->to($user->email);
                $message->subject("Hello $user->name, Verify Email Address");
            }
        );
        return response()->json(['data' => $user->toArray()], 201);
    }

    protected function activation(Request $request)
    {
        $user=User::where('email', $request->email)->first();
        if($user != null){ 
            $nowtime = strtotime(date('Y-m-d H:i:s'));
            $otptimeout = $user->session_otp+180;
                if($nowtime <= $otptimeout)  
                {
                    if ($user->otp  == $request->otp)  //after mail for activate the registration
                    {
                        $user->email_verified_at = date('Y-m-d H:i:s');
                        $user->save();  
                        return response()->json(['message' => 'Your emailid Verified. Please Login.', 'data'=>  $user->toArray()]);         
                    }else{
                        return response()->json(['message' => 'OTP is invalid, Enter Valid OTP.', 'data'=> '']);
                    }
                }else{
                    return response()->json(['message' => 'OTP Session is expired. Please try again.', 'data'=> '']);   
                }
        }
        return response()->json(['message' => 'Please Enter Register Email Id']); 
    }

    public function resend_otp(Request $request)
    {   
        $otp = mt_rand(100000, 999999);
        Session::put('otp', strtotime(date('Y-m-d H:i:s')));
        $session_otp = Session::get('otp');
        $user = User::where('email', $request->email)->first();
        if($user != Null)
        {
            $user->otp = $otp;
            $user->session_otp = $session_otp;
            $user->save(); 

            Mail::send('emails.apiVerifiedPassword',['user'=>$user,'otp'=>$otp],
                function($message)use($user)
                {
                  $message->to($user->email);
                  $message->subject("Hello ".$user->name.", Activation for Account!");
                }
            );
            return response()->json(['message' => 'One Time Password (OTP) has been sent to your registered email id, Please Enter OTP here to verify your email.', 'data'=> '']);
        }
        else{
            return response()->json(['message' => 'This email not valid, Please enter register email.', 'data'=> '']);
        }
    }
}
