<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-28
 * Time: 12:25
 */

namespace App\Http\Middleware;

use App\Exceptions\BlacklistException;
use App\Model\User;
use App\Traits\ExceptUrl;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * 用户黑名单校验中间件
 * Class UserBlacklist
 *
 * @package App\Http\Middleware
 */
class UserBlacklist
{

    use ExceptUrl;

    protected $except = [
        '/api/common/auth/*',
        '/page/common/auth/*',
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return Response
     * @throws \App\Exceptions\BlacklistException
     */
    public function handle($request, \Closure $next): Response
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }
        /** @var User $user */
        $user = $request->user();

        if (app()->environment('production') || !request()->input('nologin')) {
            if ($user && $user instanceof User && $user->getIsBlacklistedAttribute()) {
                //被拉黑名单了，拒绝
                throw new BlacklistException('Blacklisted', Arr::wrap(Auth::guard()), null);
            }
        }
        return $next($request);
    }
}
