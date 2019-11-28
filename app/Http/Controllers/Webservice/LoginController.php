<?php

namespace App\Http\Controllers\Webservice;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use session;

class LoginController extends Controller
{
   /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    // // ....Redirection of page(When user login And Status Active then goto LOGIN PAGE, When Admin login goto DASHBOARD PAGE)....
    // protected function redirectTo()
    // {
    //     if (Auth::user() && Auth::user()->role_id==1) {
    //         return '/dashboard';
    //     } else if( Auth::user() && Auth::user()->role_id==2 && Auth::user()->is_active==0){
    //         Auth::logout();
    //         return '/login';
    //     }
    //     elseif ( Auth::user() && Auth::user()->role_id==2 && Auth::user()->is_active==1) {
    //         return '/home';
    //     }
    // }

     //....User Login ....
    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            if(Auth::user()->email_verified_at != null){
                if(Auth::user()->is_active==0){
                    Auth::logout();
                    return response()->json(['error', 'Your account has not yet been activated. Please check Your email']);
                } else if (Auth::user() && Auth::user()->role_id==2) {
                    return response()->json(['message', 'Login Success','data', Auth::user()]);
                }
            }else{
                return response()->json(['message', 'Please Verify Your Email-address']);
            }
        }else {
            return response()->json(['error', 'Address email or/and password are incorrect.']);
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        // $user = Auth::guard('api')->user();
        // if ($user) {
        //     $user->api_token = null;
        //     $user->save();
        // }
        // Auth::logout();
        return  $user = Auth::user();
        $user->api_token = null;
        $user->save();
        return response()->json(['data' => 'User logged out.'], 200);
    }
}
