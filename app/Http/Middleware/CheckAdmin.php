<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin = explode(',', env('ADMIN', 'kim109,ohminsuk'));
        $admin = array_map('trim',$admin);

        if (!in_array(Auth::user()->username, $admin)) {
            Auth::logout();
            return redirect('login')->withErrors(['관리자 권한이 없습니다.']);
        }

        return $next($request);
    }
}
