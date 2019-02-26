<?php

namespace App\Providers;

use App\Model\Article;
use App\Model\User;
use App\Observers\ArticleLogObserver;
use App\Observers\UserLogObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //注册监听器
        Article::observe(ArticleLogObserver::class);
        User::observe(UserLogObserver::class);
    }
}
