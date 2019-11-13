<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
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
    // protected $redirectTo = '/home';
    protected function redirectTo()
    {
        if (Auth::user() && Auth::user()->role_id==1) {
            return '/dashboard';
        } else if( Auth::user() && Auth::user()->role_id==2 && Auth::user()->is_active==0){
            Auth::logout();
            return '/login';
        }
        elseif ( Auth::user() && Auth::user()->role_id==2 && Auth::user()->is_active==1) {
            return '/home';
        }

    }

    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if(auth()->attempt(['email'=>$request->email,'password'=>$request->password])){

            if(auth()->user()->is_active==0){
                Auth::logout();
                return back()->with('error', 'Your account has not yet been activated. Please check Your email');
            } else if (Auth::user() && Auth::user()->role_id==1) {
                return redirect()->route('dashboard');
            } else if ( Auth::user() && Auth::user()->role_id==2) {
                return redirect()->route('home');
            }
        }else {
            return back()->with('error', 'Address email or/and password are incorrect.');
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
}
