<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-27
 * Time: 13:38
 */

namespace App\Contract;

interface MustVerifyMobile
{
    /**
     * 是否已经验证手机号
     *
     * @return bool
     */
    public function hasVerifiedMobile();

    /**
     * 标记为已验证
     *
     * @return bool
     */
    public function markMobileAsVerified();

    /**
     * 发送验证短信
     *
     * @return void
     */
    public function sendMobileVerificationNotification();
}
