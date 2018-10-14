<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\Subscriber
 *
 * @property int                                                     $id
 * @property int|null                                                $uid        订阅用户UID
 * @property string                                                  $email      邮箱
 * @property string                                                  $mobile     手机号
 * @property string                                                  $scope      用户订阅信息范围：all-全部/article-博客文章/activity_new-新活动举办通知/activity_start-活动开始/score-成绩通知/job_new-新职位/job_result-职位申请反馈/comment-评论与回复通知
 * @property int                                                     $valid      是否有效，如果用户取消订阅则无效
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereValid($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\User|null                               $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Subscriber onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Subscriber withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Subscriber withoutTrashed()
 */
class Subscriber extends Model
{
    use SoftDeletes;

    protected $casts = [
        'valid' => 'boolean'
    ];

    /**
     * 订阅用户
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
