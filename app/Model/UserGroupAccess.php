<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Model\UserGroupAccess
 *
 * @property int                       $uid
 * @property int                       $group_id
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupAccess whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroupAccess whereUid($value)
 * @mixin \Eloquent
 * @property-read \App\Model\User      $user
 * @property-read \App\Model\UserGroup $userGroup
 */
class UserGroupAccess extends Pivot
{
    /**
     * 用户信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * 用户组信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'group_id');
    }
}
