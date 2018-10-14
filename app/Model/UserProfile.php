<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\UserProfile
 *
 * @property int                             $id
 * @property int                             $uid
 * @property string                          $stu_no     学号
 * @property string                          $school     学院
 * @property string                          $class      班级
 * @property string                          $name       姓名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserProfile extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
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
