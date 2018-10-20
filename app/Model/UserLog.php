<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\UserLog
 *
 * @property int                                                     $id
 * @property int                                                     $uid         用户UID
 * @property string                                                  $title       行为标题
 * @property string                                                  $description 行为描述，可以是markdown
 * @property string|null                                             $loggable_type
 * @property int|null                                                $loggable_id
 * @property int                                                     $result      行为执行结果：0-失败/1-成功
 * @property string                                                  $extra       额外信息，相关URL/相关附件ID/相关文章ID/相关活动、职位/执行结果/失败原因等
 * @property string|null                                             $ip          操作人IP
 * @property string                                                  $ua          操作人User-Agent
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at  软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLoggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLoggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent      $loggable
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\User                                    $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|UserLog onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|UserLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserLog withoutTrashed()
 */
class UserLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'title', 'description', 'result', 'extra', 'ip', 'ua'
    ];

    protected $casts = [
        'result' => 'boolean',
        'extra'  => 'array'
    ];

    /**
     * 操作用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * 产生行为的对象
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function loggable()
    {
        return $this->morphTo();
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
