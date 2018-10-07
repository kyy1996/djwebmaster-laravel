<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Signup
 *
 * @property int $id
 * @property int $activity_id 报名的活动ID
 * @property int|null $uid 报名用户UID
 * @property string $stu_no 学号
 * @property string $school 学院
 * @property string $class 班级
 * @property string $name 姓名
 * @property string $qq 联系QQ
 * @property string $mobile 通知手机号
 * @property string $email 通知邮箱
 * @property int $valid 是否有效
 * @property string $comment 报名说明
 * @property string|null $ip 报名时用的IP
 * @property string $ua 报名时所用浏览器User-Agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereQq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereValid($value)
 * @mixin \Eloquent
 */
class Signup extends Model
{
    //
}
