<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-28
 * Time: 16:11
 */

namespace App\Traits;


use Illuminate\Http\Request;

/**
 * 判断地址是否在排除地址列表中
 * Trait ExceptUrl
 *
 * @package App\Traits
 * @property-read string[] $except
 */
trait ExceptUrl
{

    /**
     * 是否在免登录地址中
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
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
