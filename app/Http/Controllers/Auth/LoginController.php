<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/dashboard';

    protected function authenticated(Request $request, $user)
    {
        if (!$user->is_admin) {
            $this->guard()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Admin access only.',
            ]);
        }

        // $request->session()->flash('status', 'Logged in successfully.');
        $request->session();
    }

    protected function loggedOut(Request $request)
    {
        // return redirect()->route('login')->with('status', 'Logged out successfully.');
        return redirect()->route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
