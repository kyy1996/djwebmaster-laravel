<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Subscriber
 *
 * @property int                                                     $id
 * @property int|null                                                $uid        订阅用户UID
 * @property string                                                  $email      邮箱
 * @property string                                                  $mobile     手机号
 * @property int                                                     $scope
 *           二进制或运算，用户订阅信息范围：0-全部/article-博客文章/activity_new-新活动举办通知/activity_start-活动开始/score-成绩通知/job_new-新职位/job_result-职位申请反馈/comment-评论与回复通知
 * @property boolean                                                 $valid      是否有效，如果用户取消订阅则无效
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
 * @mixin \Illuminate\Database\Query\Builder
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

    public $modelName = '订阅信息';

    protected $fillable = [
        'uid', 'email', 'mobile', 'scope', 'valid',
    ];

    protected $casts = [
        'valid' => 'boolean',
    ];

    protected $with = [
        'user',
    ];

    protected $appends = [
        'scope_list',
    ];

    //订阅文章
    const SCOPE_ALL = 1 << 0;
    //article-博客文章
    const SCOPE_ARTICLE_NEW = 1 << 1;
    //activity_new-新活动举办通知
    const SCOPE_ACTIVITY_NEW = 1 << 2;
    //activity_start-活动开始
    const SCOPE_ACTIVITY_START = 1 << 3;
    //score-成绩通知
    const SCOPE_SCORE_NOTICE = 1 << 4;
    //job_new-新职位
    const SCOPE_JOB_NEW = 1 << 5;
    //job_result-职位申请反馈
    const SCOPE_JOB_RESULT = 1 << 6;
    //comment-评论与回复通知
    const SCOPE_COMMENT_REPLY = 1 << 7;

    const SCOPE = [
        self::SCOPE_ALL            => '全部',
        self::SCOPE_ARTICLE_NEW    => '有新的博客文章',
        self::SCOPE_ACTIVITY_NEW   => '推出了新活动',
        self::SCOPE_ACTIVITY_START => '报名的活动开始了',
        self::SCOPE_SCORE_NOTICE   => '成绩通知',
        self::SCOPE_JOB_NEW        => '有新的职位',
        self::SCOPE_JOB_RESULT     => '职位申请反馈',
        self::SCOPE_COMMENT_REPLY  => '有新评论或回复',
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

    public function getScopeListAttribute(): array
    {
        $scope = $this->getAttribute('scope');
        if ($scope === null) return [];
        $result = [];
        foreach (static::SCOPE as $id => $value) {
            $info = [
                'id'     => $id,
                'title'  => $value,
                'status' => false,
            ];
            if ($scope === $id || ($id > 0 && ($scope & $id) == $id)) {
                $info['status'] = true;
            }
            $result[] = $info;
        }
        return $result;
    }
}
