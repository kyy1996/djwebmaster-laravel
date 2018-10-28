<?php

namespace App\Providers;

use App\Model\Activity;
use App\Model\Article;
use App\Model\Attachment;
use App\Model\Blacklist;
use App\Model\Checkin;
use App\Model\Comment;
use App\Model\Common;
use App\Model\Job;
use App\Model\JobApplication;
use App\Model\Menu;
use App\Model\Signup;
use App\Model\Subscriber;
use App\Model\User;
use App\Model\UserGroup;
use App\Model\UserLog;
use App\Model\UserProfile;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

/**
 * Class MorphMapServiceProvider
 * 多态关联用的模型名映射
 * 在数据库里会记录简短的别名，而不是完整的模型类名
 * https://laravel.com/docs/5.7/eloquent-relationships
 *
 * @package App\Providers
 * @author  alen
 */
class MorphMapServiceProvider extends ServiceProvider
{
    /**
     * 要转换为小写下划线方式的类名
     *
     * @var array
     */
    private $morph = [
        Activity::class, Article::class, Attachment::class,
        Blacklist::class, Checkin::class, Comment::class,
        Common::class, Job::class, JobApplication::class,
        Menu::class, Signup::class, Subscriber::class,
        User::class, UserLog::class, UserGroup::class,
        UserProfile::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $morphMap = [];
        foreach ($this->morph as $value) {
            $key = trim(trim($value), '/\\.');
            $key = str_replace('\\', DIRECTORY_SEPARATOR, $key);
            $key = pathinfo($key, PATHINFO_BASENAME);
//            $key            = \Illuminate\Support\Str::snake($key);
            $morphMap[$key] = $value;
        }
        Relation::morphMap($morphMap);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
