<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Job
 *
 * @property int                                                            $id
 * @property string                                                         $name        职位名称
 * @property string                                                         $department  部门
 * @property string                                                         $description 职位描述
 * @property int|null                                                       $article_id  关联文章ID
 * @property int                                                            $status      职位状态
 * @property int|null                                                       $uid         发布人UID
 * @property string|null                                                    $ip          发布人IP
 * @property \Illuminate\Support\Carbon|null                                $created_at
 * @property \Illuminate\Support\Carbon|null                                $updated_at
 * @property \Illuminate\Support\Carbon|null                                $deleted_at  软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Query\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|JobApplication[] $applications
 * @property-read \App\Model\Article|null                                   $article
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[]        $logs
 * @property-read \App\Model\User                                           $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Job onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Job withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Job withoutTrashed()
 */
class Job extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'department', 'description', 'status', 'uid', 'ip',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * 发布用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 相关文章
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * 申请信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
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
