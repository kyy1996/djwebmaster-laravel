<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserProfile
 *
 * @property int $id
 * @property int $uid
 * @property string $stu_no 学号
 * @property string $school 学院
 * @property string $class 班级
 * @property string $name 姓名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserProfile extends Model
{
    //
}
