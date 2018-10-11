<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\UserGroup
 *
 * @property int $id
 * @property string $title 用户组名称
 * @property string $description 用户组描述
 * @property int $status 用户组状态
 * @property string $rules 用户组拥有的权限规则id，多个规则 , 隔开
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserGroup extends Model
{
    //
}
