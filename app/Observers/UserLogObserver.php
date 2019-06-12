<?php

namespace App\Observers;

use App\Model\User;
use Illuminate\Support\Arr;

/**
 * 用户相关操作日志
 * Class UserLogObserver
 *
 * @package App\Observers
 */
class UserLogObserver extends CommonLogBaseObserver
{
    protected static $methodTitles = [
        'created' => '注册',
    ];

    protected static $extraFields = [
        'uid'   => 'UID',
        'email' => 'EMAIL',
    ];

    public function created(User $user): bool
    {
        return $this->__call(__FUNCTION__, Arr::wrap($user));
    }

    public function updated(User $user): bool
    {
        return $this->__call(__FUNCTION__, Arr::wrap($user));
    }

    public function deleted(User $user): bool
    {
        return $this->__call(__FUNCTION__, Arr::wrap($user));
    }
}
