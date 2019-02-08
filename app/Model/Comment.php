<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\Comment
 *
 * @property int                                                     $id
 * @property string|null                                             $commentable_type
 * @property int|null                                                $commentable_id
 * @property int|null                                                $uid        发表人用户UID
 * @property int|null                                                $parent_id  父评论ID，也就是被回复的评论ID
 * @property string                                                  $nickname   发表用户昵称
 * @property string                                                  $email      发表人邮箱
 * @property string                                                  $content    内容
 * @property string                                                  $extra      额外信息：附件ID、相关URL
 * @property string                                                  $ip         发表人IP
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Query\Builder
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent      $commentable
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\Comment|null                            $parent
 * @property-read \App\Model\User|null                               $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Comment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Comment withoutTrashed()
 */
class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'nickname', 'email', 'content', 'extra', 'ip', 'parent_id',
    ];

    protected $casts = [
        'extra' => 'array',
    ];

    /**
     * 得到评论所属的文章
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function article()
    {
        return $this->commentable();
    }

    /**
     * 得到评论所属的模型（文章/活动/附件/招聘）
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * 得到评论所属的活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function activity()
    {
        return $this->commentable();
    }

    /**
     * 得到评论所属的附件
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachment()
    {
        return $this->commentable();
    }

    /**
     * 得到评论所属的招聘信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function job()
    {
        return $this->commentable();
    }

    /**
     * 发表用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * 上级评论，也就是被回复的评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
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
