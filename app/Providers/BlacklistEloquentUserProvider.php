<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-28
 * Time: 13:38
 */

namespace App\Providers;


use App\Model\Code;
use App\Model\User;
use App\Model\UserProfile;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

/**
 * 带黑名单功能的用户提供者
 * Class BlacklistEloquentUserProvider
 *
 * @package App\Providers
 */
class BlacklistEloquentUserProvider extends EloquentUserProvider
{

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|User
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
             array_key_exists('password', $credentials))) {
            return null;
        }

        $query = $this->createModel();

        //允许：UID/EMAIL/手机号/学号
        //先判断email
        if (strpos($credentials['username'], '@') !== false) {
            //email
            $query->where('email', $credentials['username']);
            return $query->first();
        } else {
            //uid
            $model = (clone $query)->find($credentials['username']);
            if ($model) return $model;
            //手机号
            $model = (clone $query)->where('mobile', $credentials['username'])->first();
            if ($model) return $model;
            //学号
            $model = (new UserProfile())->where('stu_no', $credentials['username'])->first();
            if ($model) return $model->user;
        }
        return null;
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        if ($user instanceof User) {
            if ($user->getIsBlacklistedAttribute()) {
                //黑名单
                if (app()->environment('production') || !request()->input('nologin')) {
                    //如果不是生产环境并且nologin标记为1，就允许黑名单用户访问
                    Code::setCode(Code::ERR_USER_BLACKLIST);
                    return false;
                }
            }
        }
        return parent::validateCredentials($user, $credentials);
    }
}
