<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{

    public function handle(Request $request, Closure $next)
    {
        $local = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';
        
        app()->setLocale($local);

        return $next($request);
    }
}
