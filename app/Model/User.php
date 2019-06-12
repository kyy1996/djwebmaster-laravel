<?php

namespace App\Model;

use App\Contract\MustVerifyMobile;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Model\User
 *
 * @property int
 *               $uid
 * @property string
 *               $avatar             用户头像
 * @property string
 *               $mobile             用户手机号
 * @property string
 *               $password           加密后的用户密码
 * @property string
 *               $email              用户邮箱
 * @property int
 *               $admin              是否是管理员：0-普通用户/1-管理员
 * @property int
 *               $status             账户状态：1-启用/0-停用
 * @property string|null
 *               $email_verified_at  邮箱验证时间，为空表示邮箱还未被验证
 * @property string|null
 *               $mobile_verified_at 手机号验证时间，为空表示手机还未被验证
 * @property string|null
 *               $remember_token     记住密码TOKEN，即自动登录TOKEN，Token携带有效期
 * @property string|null
 *               $create_ip          注册IP
 * @property string|null
 *               $update_ip          更新IP
 * @property string|null
 *               $last_login_at      上次登录时间
 * @property string|null
 *               $last_login_ip      上次登录IP
 * @property \Illuminate\Support\Carbon|null
 *               $created_at
 * @property \Illuminate\Support\Carbon|null
 *               $updated_at
 * @property \Illuminate\Support\Carbon|null
 *               $deleted_at         软删除时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[]
 *                    $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *                $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[]
 *                    $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreateIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobileVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdateIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[]
 *                    $actionLogs
 * @property-read \Illuminate\Database\Eloquent\Collection|Activity[]
 *                    $activities
 * @property-read \Illuminate\Database\Eloquent\Collection|Article[]
 *                    $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|Attachment[]
 *                    $attachments
 * @property-read \App\Model\Blacklist
 *                    $blacklist
 * @property-read \Illuminate\Database\Eloquent\Collection|Checkin[]
 *                    $checkins
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[]
 *                    $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|JobApplication[]
 *                    $jobApplications
 * @property-read \Illuminate\Database\Eloquent\Collection|Job[]
 *                    $jobs
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[]
 *                    $logs
 * @property-read \App\Model\UserProfile
 *                    $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|Signup[]
 *                    $signups
 * @property-read \App\Model\Subscriber
 *                    $subscriber
 * @property-read \Illuminate\Support\Collection
 *                    $applied_jobs
 * @property-read \Illuminate\Support\Collection
 *                    $checkin_activities
 * @property-read \Illuminate\Support\Collection
 *                    $signup_activities
 * @method static bool|null forceDelete()
 * @method static Builder|User onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 */
class User extends Authenticatable implements MustVerifyEmail, MustVerifyMobile
{
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use \App\Traits\MustVerifyMobile;
    use HasRoles;

    public $modelName = '用户';

    protected $primaryKey = 'uid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar', 'mobile', 'password', 'email', 'admin', 'status', 'email_verified_at', 'mobile_verified_at',
        'remember_token', 'create_ip', 'update_ip', 'last_login_at', 'last_login_ip',
    ];

    protected $with = [
        'profile', 'roles',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'admin'  => 'boolean',
        'status' => 'boolean',
    ];

    protected $appends = [
        'checkin_activities', 'signup_activities', 'applied_jobs', 'email_verified', 'mobile_verified',
        'is_blacklisted',
    ];

    /**
     * 用户个人资料
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'uid');
    }


    /**
     * 用户创建的活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'uid');
    }

    /**
     * 用户签到的活动
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCheckinActivitiesAttribute(): Collection
    {
        return $this->checkins()->pluck('activity_id');
    }

    /**
     * 用户签到
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class, 'uid')->with('activity');
    }

    /**
     * 用户报名的活动
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSignupActivitiesAttribute(): Collection
    {
        return $this->signups()->pluck('activity_id');
    }

    /**
     * 用户报名
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function signups(): HasMany
    {
        return $this->hasMany(Signup::class, 'uid')->with('activity');
    }

    /**
     * 用户创建的招聘信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'uid');
    }

    /**
     * 用户申请的职位
     *
     * @return \Illuminate\Support\Collection|string[]
     */
    public function getAppliedJobsAttribute(): Collection
    {
        return $this->jobApplications()->pluck('job_id');
    }

    /**
     * 用户的职位申请信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'uid')->with('job_id');
    }

    /**
     * 用户的订阅信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriber(): HasMany
    {
        return $this->hasMany(Subscriber::class, 'uid');
    }

    /**
     * 用户的评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'uid');
    }

    /**
     * 用户发表的文章
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'uid');
    }

    /**
     * 用户上传的附件
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'uid');
    }

    /**
     * 用户在黑名单的信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function blacklist(): HasOne
    {
        return $this->hasOne(Blacklist::class, 'uid');
    }

    /**
     * 用户操作记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionLogs(): HasMany
    {
        return $this->hasMany(UserLog::class, 'uid');
    }

    /**
     * 相关的行为记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }

    /**
     * 是否验证过email
     *
     * @return bool
     */
    public function getEmailVerifiedAttribute(): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * 是否验证过email
     *
     * @return bool
     */
    public function getMobileVerifiedAttribute(): bool
    {
        return $this->getAttribute('mobile_verified_at') !== null;
    }

    /**
     * 是否在黑名单内
     *
     * @return bool
     */
    public function getIsBlacklistedAttribute(): bool
    {
        $blacklist = $this->blacklist()->where('valid', 1)->first();
        return $blacklist !== null;
    }
}
