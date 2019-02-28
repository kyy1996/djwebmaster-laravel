<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-27
 * Time: 19:06
 */

namespace App\Http\Middleware;

use App\Traits\ExceptUrl;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

/**
 * 权限中间件
 * Class Permission
 *
 * @package App\Http\Middleware
 */
class Permission
{
    use ExceptUrl;

    protected $except = [
        '/api/common/auth/*',
        '/page/common/auth/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, \Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        if ($this->inExceptArray($request) ||
            (!app()->environment('production') && $request->input('nologin'))) {
            //如果在免登录地址内，或者调试模式，直接通过
            return $next($request);
        }
        /** @var \App\Model\User $currentUser */
        $currentUser = Auth::user();
        if (!$currentUser) {
            //没有用户信息，直接跳过，可能是noLogin
            return $next($request);
        }
        //得到当前路由地址
        $permits = strtolower($this->getRoute($request));
        try {
            \App\Model\Permission::findByName($permits);
        } catch (PermissionDoesNotExist $e) {
            //没有配置，直接通过
            return $next($request);
        }
        if ($currentUser->admin || $currentUser->can($permits)) {
            //有权限，继续操作
            return $next($request);
        }
        throw new AuthorizationException();
    }

    /**
     * 使用路由，去除prefix，作为权限name
     *
     * @param $request
     * @return string
     */
    public function getRoute(Request $request)
    {
        $routeAction = $request->route()->getAction();
        $currentUri  = $request->getPathInfo();

        $prefix = $routeAction['prefix'];
        if ($prefix[0] !== '/') {
            $prefix = '/' . $prefix;
        }
        if (!empty($prefix)) {
            $currentUri = substr($currentUri, strlen($prefix));
        }

        return strtolower($currentUri);
    }
}
