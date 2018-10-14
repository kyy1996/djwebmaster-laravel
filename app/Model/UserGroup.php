<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\UserGroup
 *
 * @property int                             $id
 * @property string                          $title       用户组名称
 * @property string                          $description 用户组描述
 * @property int                             $status      用户组状态
 * @property string                          $rules       用户组拥有的权限规则id，多个规则 , 隔开
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at  软删除时间
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
    use SoftDeletes;

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * 用户组中的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'uid')
                    ->using(UserGroupAccess::class);
    }

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
