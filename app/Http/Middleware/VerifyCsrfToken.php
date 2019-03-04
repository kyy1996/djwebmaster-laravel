<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/ajax/common/auth/*',
        '/api/page/common/auth/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next): Response
    {
        //如果传入了nocsrf标记，认为不校验csrf，直接通过
        if (!app()->environment('production') && $request->input('nocsrf', false) == 1) {
            return $next($request);
        }
        return parent::handle($request, $next);
    }
}
