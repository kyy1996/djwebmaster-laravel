<?php

namespace App\Observers;

use App\Common\Util;
use App\Model\UserLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * 日志记录器基础
 * Class CommonLogObserver
 *
 * @package App\Observers
 */
class CommonLogBaseObserver
{
    protected $userLog = [];

    protected $model = null;

    protected static $messages = [
        'created'   => '创建',
        'updated'   => '修改',
        'deleted'   => '删除',
        'retrieved' => '查看',
    ];

    public function __construct()
    {
        //初始化基础信息
        $this->userLog['ip']     = Util::getUserIp();
        $this->userLog['uid']    = Auth::id();
        $this->userLog['ua']     = request()->userAgent();
        $this->userLog['result'] = true;
    }

    public function __destruct()
    {
        $userLog = new UserLog($this->userLog);
        if ($this->model instanceof Model) {
            $userLog->loggable()->associate($this->model);
        }
        $userLog->save();
    }

    protected function messages()
    {
        return Util::arrayRecursiveMerge(self::$messages, static::$messages);
    }

    protected function getMessage($event)
    {
        return $this->messages()[$event] ?? $event;
    }

    public function __call($method, array $args)
    {
        $model                        = $args[0];
        $class                        = get_class($model);
        $baseClassName                = basename(str_replace('\\', DIRECTORY_SEPARATOR, $class));
        $modelName                    = property_exists($model, 'modelName') ? $model->modelName : $baseClassName;
        $uid                          = $model->uid;
        $actionName                   = $this->getMessage($method);
        $primaryKeyName               = $model->getRouteKeyName();
        $primaryKeyValue              = $model->{$primaryKeyName};
        $this->userLog['title']       = sprintf('[%s][%s]用户[%d]%s了%s为%d的%s', $baseClassName, $method, $uid, $actionName, $primaryKeyName, $primaryKeyValue, $modelName);
        $this->userLog['description'] = sprintf('用户[%d]%s了%s为%d的%s', $uid, $actionName, $primaryKeyName, $primaryKeyValue, $modelName);
        $this->model                  = $model;
        return true;
    }
}
