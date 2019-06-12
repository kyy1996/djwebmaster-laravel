<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
 * @property string                                                  $storage    存储类型：local-本地
 * @property string                                                  $url        文件下载地址
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\User|null                               $user
 * @method static Builder|Attachment whereCount($value)
 * @method static Builder|Attachment whereCreatedAt($value)
 * @method static Builder|Attachment whereDeletedAt($value)
 * @method static Builder|Attachment whereExtension($value)
 * @method static Builder|Attachment whereExtra($value)
 * @method static Builder|Attachment whereFilename($value)
 * @method static Builder|Attachment whereId($value)
 * @method static Builder|Attachment whereMd5($value)
 * @method static Builder|Attachment whereMime($value)
 * @method static Builder|Attachment wherePath($value)
 * @method static Builder|Attachment whereSize($value)
 * @method static Builder|Attachment whereUid($value)
 * @method static Builder|Attachment whereUpdatedAt($value)
 * @method static Builder|Attachment whereValid($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Attachment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Attachment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Attachment withoutTrashed()
 * @method static Builder|Attachment whereStorage($value)
 * @method static Builder|Attachment whereUrl($value)
 * @mixin \Illuminate\Database\Query\Builder
 */
class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'filename', 'storage', 'path', 'url', 'size', 'md5', 'mime', 'extension', 'extra', 'valid', 'count',
    ];

    protected $casts = [
        'valid' => 'boolean',
        'extra' => 'array',
    ];

    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null $file
     * @param string                                                                   $directory
     * @param string                                                                   $storageDisk oss|public|local
     * @return \App\Model\Attachment|false
     * @throws \Throwable
     */
    public static function upload($file, string $directory, string $storageDisk = '')
    {
        mb_regex_encoding('utf-8');
        $root      = '/' . config('filesystems.root');
        $directory = $root . '/' . $directory;
        $directory = mb_ereg_replace('[\\/\\\]{2,}', DIRECTORY_SEPARATOR, $directory);
        $directory = rtrim($directory, '/\\');
        if (!$storageDisk) {
            $storageDisk = Storage::getDefaultDriver();
        }
        $md5        = md5_file($file->getRealPath());
        $attachment = self::whereStorage($storageDisk)->where('md5', $md5)->first();
        if ($attachment) {
            $attachment->count++;
            $attachment->save();
            return $attachment;
        }
        $disk      = Storage::disk($storageDisk);
        $storePath = $disk->put($directory, $file);
        if (!$storePath) {
            Code::setCode(Code::ERR_FAIL);
            return false;
        }
        $url        = method_exists($disk, 'url') ? $disk->url($storePath) : asset($storePath);
        $response   = [
            'uid'       => Auth::id(),
            'filename'  => $file->getClientOriginalName(),
            'storage'   => $storageDisk,
            'path'      => $storePath,
            'url'       => $url,
            'size'      => $file->getSize(),
            'md5'       => $md5,
            'mime'      => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ];
        $attachment = new self($response);
        $attachment->saveOrFail();
        return $attachment;
    }

    /**
     * 上传的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * 附件相关的评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * 相关记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }
}
