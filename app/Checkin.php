<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Checkin
 *
 * @property int $id
 * @property int $activity_id 签到的活动ID
 * @property int|null $uid 签到用户UID
 * @property string $stu_no 学号
 * @property string $school 学院
 * @property string $class 班级
 * @property string $name 姓名
 * @property int $valid 是否有效
 * @property string $comment 备注
 * @property string|null $ip 签到时用的IP
 * @property string $ua 签到时所用浏览器User-Agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Checkin whereValid($value)
 * @mixin \Eloquent
 */
class Checkin extends Model
{
    //
}
