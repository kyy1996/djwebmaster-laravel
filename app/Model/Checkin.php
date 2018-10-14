<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Checkin
 *
 * @property int                             $id
 * @property int                             $activity_id 签到的活动ID
 * @property int|null                        $uid         签到用户UID
 * @property string                          $stu_no      学号
 * @property string                          $school      学院
 * @property string                          $class       班级
 * @property string                          $name        姓名
 * @property int                             $valid       是否有效
 * @property string                          $comment     备注
 * @property string|null                     $ip          签到时用的IP
 * @property string                          $ua          签到时所用浏览器User-Agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at  软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkin whereValid($value)
 * @mixin \Eloquent
 */
class Checkin extends Model
{
    use SoftDeletes;

    protected $casts = [
        'valid' => 'boolean'
    ];

    /**
     * 签到的活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * 签到的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
