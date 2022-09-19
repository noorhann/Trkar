<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App;
use App\Helpers\Helper;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            if (!Helper::adminCan(\Request::route()->getName())) {
                abort(403);
            }
            // Language
            \App::setLocale(Auth::user()->language);
            if (
                !empty($request->system_language) &&
                in_array($request->system_language, ['ar', 'en'])
            ) {
                Auth::user()->update([
                    'language' => $request->system_language
                ]);

                \App::setLocale($request->system_language);
            }
            return $next($request);
        } else {
            return redirect('admin/login')->with('status', 'Please Login First');
        }
    }
}
