<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-07
 * Time: 23:45
 */

namespace App\Common;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ReflectionClass;

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

    /**
     * 数据集转树数组
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $list
     * @param string                                        $id
     * @param string                                        $pid   pidKey
     * @param string                                        $child 子项key
     * @param int                                           $root  根节点的pid值
     * @return array
     */
    public static function list2tree($list, $id = "id", $pid = "parent_id", $child = "_child", $root = null)
    {
        ($list instanceof Arrayable) && $list = $list->toArray();
        $refer = [];
        //转换为id数组
        foreach ($list as $key => $item) {
            $refer[$item[$id]] = &$list[$key];
        }
        $tree = [];
        //构造树
        foreach ($list as $key => $item) {
            if ($item[$pid] === $root) {
                //根节点
                $tree[$item[$id]] = &$list[$key];
            } else {
                if (key_exists($item[$pid], $refer)) {
                    $refer[$item[$pid]][$child][] = &$list[$key];
                }
            }
        }

        return $tree;
    }

    /**
     * 根据菜单层级将标题转换为树形标题
     *
     * @param array  &$tree       要转换的树形数组
     * @param string  $delimiter  标题分隔符
     * @param string  $childKey   树形菜单中的子菜单key
     * @param string  $titleKey   标题Key
     * @param array   $parentInfo 上级菜单信息
     * @return void
     */
    public static function convertNestedTitleTree(array &$tree, string $delimiter = ' - ', string $childKey = '_child', string $titleKey = 'title', array $parentInfo = []): void
    {
        foreach ($tree as &$childItem) {
            if ($parentInfo) {
                $childItem[$titleKey] = $parentInfo[$titleKey] . $delimiter . $childItem[$titleKey];
            }
            if (!isset($childItem[$childKey]) || !$childItem[$childKey]) continue;
            static::convertNestedTitleTree($childItem[$childKey], $delimiter, $childKey, $titleKey, $childItem);
        }
    }

    /**
     * 树转换为指定层级的树
     *
     * @param array  $tree
     * @param int    $level
     * @param string $childKey
     * @return array
     */
    public static function tree2list(array $tree, int $level = 1, string $childKey = '_child'): array
    {
        if ($level > 1) {
            --$level;
            foreach ($tree as &$item) {
                if (@$item[$childKey] && is_array($item[$childKey])) {
                    $item[$childKey] = static::tree2list($item[$childKey], $level, $childKey);
                }
            }
        } else {
            $resultTree = [];
            foreach ($tree as $current) {
                $currentItem = $current;
                if (@$currentItem[$childKey] && is_array($currentItem[$childKey])) {
                    $currentItem[$childKey] = [];
                }
                $resultTree[] = $currentItem;
                //如果子树元素存在
                if (@$current[$childKey] && is_array($current[$childKey])) {
                    //那就遍历这颗子树，把每个元素拿出来
                    while ($childItem = array_shift($current[$childKey])) {
                        //每个子元素也有可能有下一级的树，继续遍历
                        $grandTree = $childItem[$childKey] ?? null;
                        is_array($grandTree) && $childItem[$childKey] = [];
                        $resultTree[] = $childItem;
                        if ($grandTree && is_array($grandTree)) {
                            //把下一级的树存下来，把这个子元素插进去
                            foreach (static::tree2list($grandTree, $level, $childKey) as $grandItem) {
                                $resultTree[] = $grandItem;
                            }
                        }
                    }
                }
            }
            $tree = $resultTree;
        }
        return $tree;
    }

    /**
     * 通用路由绑定方法
     *
     * @param string $subModule
     * @return \Closure
     */
    public static function commonRouteBindCallback(string $subModule = ''): \Closure
    {
        return function (\Illuminate\Routing\Router $router) use ($subModule) {
            $router->any('{module}/{controller}/{action?}', function (string $module, string $controller, string $action = 'index') use ($subModule) {
                $method     = strtolower(request()->method());
                $module     = ucfirst($module);
                $action     = $method . ucfirst($action);
                $controller = ucfirst($controller) . 'Controller';

                $namespaces = [
                    $subModule ? 'Modules' : 'App',
                    $subModule,
                    'Http',
                    'Controllers',
                    $module,
                    $controller,
                ];
                $namespaces = array_filter($namespaces);
                $className  = implode('\\', $namespaces);
                //region 检查控制器与方法是否存在
                if (!class_exists($className)) {
                    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($className);
                }
                $clazz = new ReflectionClass($className);
                if (!$clazz->hasMethod($action)) {
                    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(!app()->environment('production') ? $className . ':' . $action : null);
                }
                //endregion
                return \request()->route()->uses($className . '@' . $action)->run();
            });
        };
    }
}
