<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-27
 * Time: 11:24
 */

namespace App\Http\Middleware;


use App\Contract\MustVerifyMobile;
use App\Http\Response\JsonResponse;
use App\Model\Code;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailOrMobileIsVerified extends EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, \Closure $next)
    {
        $user = $request->user();
        if (!$user ||
            (($user instanceof MustVerifyEmail &&
              !$user->hasVerifiedEmail()) &&
             ($user instanceof MustVerifyMobile &&
              !$user->hasVerifiedMobile()))) {
            Code::setCode(Code::ERR_VERIFIED_ONLY);
            return new JsonResponse();
        }

        return $next($request);
    }
}
