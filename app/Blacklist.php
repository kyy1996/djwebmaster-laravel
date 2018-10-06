<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Blacklist
 *
 * @property int $id
 * @property int|null $uid 被拉黑的用户UID
 * @property string $stu_no 学号
 * @property string $class 班级
 * @property string $name 姓名
 * @property string $comment 备注
 * @property int $valid 是否有效
 * @property int $operator_uid 操作用户UID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereOperatorUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereValid($value)
 * @mixin \Eloquent
 */
class Blacklist extends Model
{
    //
}
