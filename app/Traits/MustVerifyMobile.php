<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-27
 * Time: 13:44
 */

namespace App\Traits;


use App\Notifications\VerifyMobile;

/**
 * 校验手机号
 * Trait MustVerifyMobile
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Foundation\Auth\User
 * @mixin \Illuminate\Notifications\Notifiable
 * @package App\Traits
 */
trait MustVerifyMobile
{
    /**
     * 是否已经验证手机号
     *
     * @return bool
     */
    public function hasVerifiedMobile()
    {
        return !is_null($this->getAttribute('mobile_verified_at'));
    }

    /**
     * 标记为已验证
     *
     * @return bool
     */
    public function markMobileAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * 发送验证短信
     *
     * @return void
     */
    public function sendMobileVerificationNotification()
    {
        $this->notify(new VerifyMobile());
    }
}
