<?php

namespace App\Http\Middleware;

use Auth;
use App\Setting;
use Closure;

class CheckStatus
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
        $setting = Setting::findOrFail(1, ['status']);

        if ($setting->status == 'ready') {
            if (Auth::check()) {
                Auth::logout();
            }
            return redirect('login')->withErrors(['현재 진행중인 모의투자가 없습니다.']);
        }

        return $next($request);
    }
}
