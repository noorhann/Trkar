<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthFormRequest;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class AuthController extends SystemController
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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginForm()
    {

        return $this->view('auth.login');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        return redirect('admin/login');
    }

    public function login(AuthFormRequest $request)
    {
        $this->incrementLoginAttempts($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->response(false, 330005, 'we are unable to log you in at this time. try again later');
        }

        // Check credential
        if(
            !Auth::validate([
                'email'=> $request->email,
                'password'=> $request->password
            ])
            ){
                return $this->response(false,330005,'Invalid login credential');
            }
       
        $admin = Admin::where('email',$request->email)->firstOrFail();
        if ($admin->status == 0) {
            return $this->response(false, 330005, 'Account is In-Active');
        }
        $login = auth()->attempt([
            'email'    => $request->email,
            'password' => $request->password
        ]);
        if (!$login) {
            return $this->response(false, 330005, 'Invalid login credential');
        }

        $admin->last_login = Carbon::now();
        $admin->save();
        return $this->response(true, 200, 'Success Login', [
            'url' => url('admin')
        ]);
    }
}
