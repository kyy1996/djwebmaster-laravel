<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Blacklist
 *
 * @property int                                                     $id
 * @property int|null                                                $uid          被拉黑的用户UID
 * @property string                                                  $stu_no       学号
 * @property string                                                  $class        班级
 * @property string                                                  $name         姓名
 * @property string                                                  $comment      备注
 * @property int                                                     $valid        是否有效
 * @property int                                                     $operator_uid 操作用户UID
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at   软删除时间
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
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\User                                    $operator
 * @property-read \App\Model\User|null                               $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Blacklist withoutTrashed()
 */
class Blacklist extends Model
{

    use SoftDeletes;

    public $modelName = '黑名单信息';

    protected $fillable = [
        'uid', 'stu_no', 'comment', 'valid', 'operator_uid',
    ];

    protected $casts = [
        'valid' => 'boolean',
    ];

    protected $with = [
        'user', 'operator', 'logs',
    ];

    /**
     * 被拉黑的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * 操作人用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_uid');
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
