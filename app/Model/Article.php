<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Article
 *
 * @property int                                                      $id
 * @property int                                                      $uid           发表用户UID
 * @property string                                                   $title         文章标题
 * @property string|null                                              $content       文章内容
 * @property string                                                   $cover_img     封面图片URL
 * @property array                                                    $tags          文章标签JSON数组
 * @property boolean                                                  $hide          是否隐藏
 * @property int                                                      $read_count    阅读次数
 * @property int                                                      $comment_count 评论数量
 * @property array                                                    $extra         文章额外信息JSON对象，可以扩展附件，活动ID
 * @property string|null                                              $ip            发表人IP
 * @property \Illuminate\Support\Carbon|null                          $deleted_at    软删除时间
 * @property \Illuminate\Support\Carbon|null                          $created_at
 * @property \Illuminate\Support\Carbon|null                          $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCoverImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereReadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|Activity[] $activities
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[]  $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|Job[]      $jobs
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[]  $logs
 * @property-read \App\Model\User|null                                $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Article onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Article withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Article withoutTrashed()
 */
class Article extends Model
{
    use SoftDeletes;

    public $modelName = '文章';

    protected $fillable = [
        'uid', 'title', 'content', 'cover_img', 'tags', 'hide', 'read_count', 'comment_count', 'extra', 'ip',
    ];

    protected $casts = [
        'tags'  => 'array',
        'extra' => 'array',
        'hide'  => 'boolean',
    ];

    protected $with = [
        'user', 'activities', 'jobs', 'comments',
    ];

    /**
     * 文章所属用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * 与文章有关的活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * 与文章有关的招聘信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * 文章的评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
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
