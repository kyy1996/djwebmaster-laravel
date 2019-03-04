<?php

namespace App\Http\Middleware;

use App\Model\User;
use App\Traits\ExceptUrl;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    use ExceptUrl;

    protected $except = [
        '/api/ajax/common/auth/*',
        '/api/page/common/auth/*',
    ];

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        return route('login');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param  string[]                $guards
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->inExceptArray($request)) {
            //如果在免登录地址内，直接通过
            return $next($request);
        }
        //调试模式就免登录
        if (!app()->environment('production') && $request->input('nologin', false) && !$request->user()) {
            $user = User::find(1);
            if ($user) {
                Auth::login($user);
                return $next($request);
            }
        }
        return parent::handle($request, $next, $guards);
    }
}
