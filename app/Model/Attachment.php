<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\Attachment
 *
 * @property int                                                     $id
 * @property int|null                                                $uid        上传用户UID
 * @property string                                                  $filename   原始文件名
 * @property string                                                  $path       文件存放绝对路径：URL或者绝对路径
 * @property int                                                     $size       附件大小（字节）
 * @property string                                                  $md5        文件MD5
 * @property string                                                  $mime       MIME类型
 * @property string                                                  $extension  文件扩展名
 * @property string                                                  $extra      附件额外属性JSON对象，可以保存媒体长度，图片大小，引用数量等
 * @property int                                                     $valid      文件是否有效（文件读不到就无效）
 * @property int                                                     $count      附件被上传了几次
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at 记录被软删除的时间（文件不一定被删除）
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereMd5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereValid($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\User|null                               $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Attachment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Attachment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Attachment withoutTrashed()
 */
class Attachment extends Model
{
    use SoftDeletes;

    protected $casts = [
        'valid' => 'boolean',
        'extra' => 'array'
    ];

    /**
     * 上传的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * 附件相关的评论
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
