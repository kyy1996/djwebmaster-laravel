<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-07
 * Time: 23:45
 */

namespace App\Common;


use Illuminate\Http\Request;

/**
 * 工具类
 * Class Util
 *
 * @package App\Common
 */
class Util
{
    /**
     * 得到用户真实IP
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public static function getUserIp(Request $request): string
    {
        $ip = '';
        if ($request->server('HTTP_CLIENT_IP') && strcasecmp($request->server('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = $request->server('HTTP_CLIENT_IP');
        }
        if ($request->server('HTTP_X_FORWARDED_FOR') && strcasecmp($request->server('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip  = $request->server('HTTP_X_FORWARDED_FOR');
            $ips = explode(', ', $request->server('HTTP_X_FORWARDED_FOR'));
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = '';
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match('/^(10│172.16│192.168)\./', $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        } else if ($request->server('REMOTE_ADDR') && strcasecmp($request->server('REMOTE_ADDR'), 'unknown')) {
            $ip = $request->server('REMOTE_ADDR');
        } else if ($request->server('REMOTE_ADDR') && strcasecmp($request->server('REMOTE_ADDR'), 'unknown')) {
            $ip = $request->server('REMOTE_ADDR');
        }
        $ip && ($ip = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '');
        return $ip;
    }
}
