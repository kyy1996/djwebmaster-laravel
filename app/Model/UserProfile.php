<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\Model\UserProfile
 *
 * @property int                                                     $uid
 * @property string                                                  $stu_no     学号
 * @property string                                                  $school     学院
 * @property string                                                  $class      班级
 * @property string                                                  $name       姓名
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Query\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\User                                    $user
 * @method static bool|null forceDelete()
 * @method static Builder|UserProfile onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|UserProfile withTrashed()
 * @method static Builder|UserProfile withoutTrashed()
 */
class UserProfile extends Model
{
    protected $primaryKey = 'uid';

    public $incrementing = false;

    use SoftDeletes;

    protected $fillable = [
        'stu_no', 'school', 'class', 'name', 'created_at', 'updated_at', 'deleted_at',
    ];
    protected $visible  = [
        'stu_no', 'school', 'class', 'name', 'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * 所属用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uid');
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
