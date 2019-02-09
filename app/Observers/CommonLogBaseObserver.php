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
    //日志数据数组
    const EXTRA_FIELD_SEPARATOR           = ' | ';
    const EXTRA_FIELD_KEY_VALUE_SEPARATOR = ' : ';
    protected $userLog = [];

    //要绑定的模型
    protected $model = null;

    //事件标题
    protected static $methodTitles = [
        'created'   => '创建',
        'updated'   => '修改',
        'deleted'   => '删除',
        'retrieved' => '查看',
    ];

    //日志标题模板
    protected static $title = '[:baseModelName][:method]用户[:uid]:methodTitle了:idKey为:id的:modelTitle';
    //日志描述内容模板
    protected static $description = '用户[:uid]:methodTitle了:idKey为:id的:modelTitle:extra';
    //额外体现在日志中的模型属性
    protected static $extraFields = [];

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

    protected function methodTitles(): array
    {
        return Util::arrayRecursiveMerge(self::$methodTitles, static::$methodTitles);
    }

    protected function getMethodTitle(string $event): string
    {
        return $this->methodTitles()[$event] ?? $event;
    }

    public function __call($method, array $args): bool
    {
        $model           = $args[0];
        $class           = get_class($model);
        $baseClassName   = $this->getBaseModelName($class);
        $modelName       = $this->getModelTitle($class) ?: $baseClassName;
        $uid             = $model->uid ?? 0;
        $methodTitle     = $this->getMethodTitle($method);
        $primaryKeyName  = $model->getRouteKeyName();
        $primaryKeyValue = $model->{$primaryKeyName};
        $extraFields     = $this->extraFields();
        if ($extraFields && $model) {
            $extraFieldValue = $this->getExtraFieldValueFromModel($extraFields, $model);
            if ($extraFieldValue) {
                array_unshift($extraFieldValue, '');
                $extra = implode(self::EXTRA_FIELD_SEPARATOR, $extraFieldValue);
            }
        }
        $replacement = [
            ':baseModelName' => $baseClassName,
            ':method'        => $method,
            ':uid'           => $uid,
            ':methodTitle'   => $methodTitle,
            ':idKey'         => $primaryKeyName,
            ':id'            => $primaryKeyValue,
            ':modelTitle'    => $modelName,
            ':extra'         => $extra ?? '',
        ];
        uksort($replacement, function ($key1, $key2) {
            return strlen($key2) - strlen($key1);
        });
        $this->userLog['title']       = str_replace(array_keys($replacement), array_values($replacement), static::$title);
        $this->userLog['description'] = str_replace(array_keys($replacement), array_values($replacement), static::$description);
        $this->model                  = $model;
        return true;
    }

    /**
     * 得到需要在日志中体现的额外模型字段
     *
     * @return array
     */
    protected function extraFields(): array
    {
        return Util::arrayRecursiveMerge(self::$extraFields, static::$extraFields);
    }

    /**
     * @param string $class
     * @return string
     */
    private function getBaseModelName(string $class): string
    {
        return basename(str_replace('\\', DIRECTORY_SEPARATOR, $class));
    }

    /**
     * 得到模型标题
     *
     * @param Model|string $modelClass
     * @return string
     */
    private function getModelTitle($modelClass): string
    {
        if (is_string($modelClass) && class_exists($modelClass)) {
            $clazz = new $modelClass();
        } else if ($modelClass instanceof Model) {
            $clazz = $modelClass;
        } else {
            return '';
        }
        if (property_exists($clazz, 'modelName')) {
            return $clazz->modelName;
        }
        return '';
    }

    /**
     * 从模型中得到指定的键值对描述数组
     *
     * @param array                               $extraFields
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    private function getExtraFieldValueFromModel(array $extraFields, Model $model): array
    {
        $extraFieldValue = [];
        foreach ($extraFields as $key => $title) {
            if ($value = $model->getAttribute($key)) {
                $extraFieldValue[] = $title . self::EXTRA_FIELD_KEY_VALUE_SEPARATOR . $value;
            }
        }
        return $extraFieldValue;
    }
}
