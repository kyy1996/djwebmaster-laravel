<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-07
 * Time: 23:45
 */

namespace App\Common;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
    public static function getUserIp(Request $request = null): string
    {
        if ($request === null) {
            $request = request();
        }
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

    /**
     * 递归合并数组
     *
     * @param array $arr1      被合并的数组（会被覆盖的数组）
     * @param array ...$arrays 要合并的数组表列
     * @return array
     */
    public static function arrayRecursiveMerge(array &$arr1, array ...$arrays): array
    {
        foreach ($arrays as &$array) {
            foreach ($array as $key => $item) {
                if (is_array($item) && Arr::exists($arr1, $key) && is_array($arr1[$key])) {
                    $item = Util::arrayRecursiveMerge($arr1[$key], $item);
                }
                $arr1[$key] = $item;
            }
        }
        return $arr1;
    }

    /**
     * 统一转为json
     *
     * @param mixed $item
     * @param int   $options
     * @return false|string
     */
    public static function toJson($item, $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string
    {
        return json_encode($item, $options);
    }

    /**
     * 从JSON转为数组
     *
     * @param string $json
     * @param bool   $assoc
     * @param int    $options
     * @return false|string
     */
    public static function fromJson($json, $assoc = false, $options = JSON_THROW_ON_ERROR)
    {
        return json_decode($json, $assoc, 512, $options);
    }
}
