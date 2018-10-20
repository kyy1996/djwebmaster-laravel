<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\Activity
 *
 * @property int                                                     $id
 * @property string                                                  $name          活动名称
 * @property string                                                  $type          活动类型：教学活动/游戏/面基/xxx
 * @property string                                                  $location      活动举办地点
 * @property string                                                  $host          活动主持人：孔元元，或者孔元元/王一帅，斜杠分割多个人
 * @property string                                                  $time          活动举办时间：周三20:00
 * @property string                                                  $comment       活动备注
 * @property array                                                   $extra         额外信息：相关附件ID
 * @property int                                                     $article_id    活动关联文章ID，可为空
 * @property array                                                   $host_uids     活动主持人对应UID数组：[1,2]，因为一个活动可以有多个人来主持
 * @property int                                                     $availability  活动可容纳人数
 * @property int                                                     $signup_amount 活动报名人数，程序自动更新
 * @property boolean                                                 $hide          是否属于隐藏活动
 * @property boolean                                                 $pause         是否暂停该活动
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at    软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereHostUids($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity wherePause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSignupAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUpdatedAt($value)
 * @property-read \App\Model\Article                                 $article
 * @property-read \Illuminate\Database\Eloquent\Collection|Checkin[] $checkins
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \Illuminate\Database\Eloquent\Collection|Signup[]  $signups
 * @property-read \Illuminate\Support\Collection                     $checkin_users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Activity onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Activity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Activity withoutTrashed()
 * @mixin \Eloquent
 */
class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'type', 'location', 'host', 'time', 'comment', 'extra', 'article_id',
        'host_uids', 'availability', 'signup_amount', 'hide', 'pause'
    ];

    protected $casts = [
        'extra'     => 'array',
        'host_uids' => 'array',
        'hide'      => 'boolean',
        'pause'     => 'boolean'
    ];

    protected $appends = [
        'checkinUsers'
    ];

    /**
     * 讲师用户与个人信息
     *
     * @return User|\Illuminate\Database\Eloquent\Builder
     */
    public function hosts()
    {
        return User::with("profile")->whereIn('uid', $this->getAttribute('host_uids'));
    }

    /**
     * 活动所属文章
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * 活动的评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * 活动报名信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function signups()
    {
        return $this->hasMany(Signup::class);
    }

    /**
     * 签到的用户
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCheckinUsersAttribute()
    {
        return $this->checkins()->pluck('user');
    }

    /**
     * 签到信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkins()
    {
        return $this->hasMany(Checkin::class)->with('user');
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
