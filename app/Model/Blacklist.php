<?php

namespace App\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereOperatorUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist whereValid($value)
 * @mixin \Eloquent
 */
class Blacklist extends Model
{
    //
}
