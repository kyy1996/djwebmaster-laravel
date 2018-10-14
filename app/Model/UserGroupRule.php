<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\UserGroupRule
 *
 * @property int                             $id
 * @property string                          $module 权限规则所属模块
 * @property string                          $name   权限节点标识符，可以是：控制器/方法
 * @property string                          $title  权限节点名称
 * @property int                             $status 是否启用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupRule whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupRule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupRule whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupRule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserGroupRule extends Model
{
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * 相关记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs()
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }
}
