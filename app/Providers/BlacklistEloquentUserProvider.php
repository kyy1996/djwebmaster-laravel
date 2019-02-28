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
