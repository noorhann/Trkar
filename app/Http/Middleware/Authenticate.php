<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;
use Request;

class Authenticate extends Middleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard = null)
    {
       if (auth()->guard('vendor')->check() ) {
            return $next($request);
       
        }

        if (auth()->guard('admin')->check() ) 
        {
            return $next($request);

        }

        if (auth()->guard('api')->check() ) 
        {
            return $next($request);

        }
       /* if (auth()->guard('admins')->check() ) 
        {
            return $next($request);
        }*/
        return response()->json(['status'=>false,
        'message'=>trans('app.validation_error'),
        'code'=>401],
        401);
    }
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
