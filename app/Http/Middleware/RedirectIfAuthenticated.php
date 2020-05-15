<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
//            return redirect(RouteServiceProvider::HOME);
            $message = $request->is('signup') ? '您已经注册并已登录～' : '你已登录，请不要再操作了～';
            session()->flash('success',$message);
            return redirect('/');
        }

        return $next($request);
    }
}
