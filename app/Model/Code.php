<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-03
 * Time: 23:26
 */

namespace App\Model;

/**
 * 错误码
 * Class Code
 *
 * @package App\Model
 */
class Code
{
    private static $code    = self::SUCCESS;
    private static $message = null;

    public const SUCCESS                = 0;
    public const ERR_FAIL               = 10001;
    public const ERR_URL_ERROR          = 10002;
    public const ERR_MODEL_NOT_FOUND    = 10003;
    public const ERR_PARAMETER          = 10004;
    public const ERR_DB_FAIL            = 10005;
    public const ERR_INVALID_CREDENTIAL = 10006;
    public const ERR_INVALID_USER       = 10007;
    public const ERR_SEND_EMAIL_FAIL    = 10008;
    public const ERR_VERIFIED_ONLY      = 10009;
    public const ERR_CSRF               = 10010;
    public const ERR_NOT_LOGIN          = 10011;
    public const ERR_NO_PERMISSION      = 10012;

    public const DEFAULT_MESSAGE = '未定义';

    private static $messages = [
        self::SUCCESS                => '成功',
        self::ERR_FAIL               => '失败',
        self::ERR_URL_ERROR          => '没有找到请求的地址',
        self::ERR_MODEL_NOT_FOUND    => '没有找到请求的资源',
        self::ERR_PARAMETER          => '参数错误',
        self::ERR_DB_FAIL            => '数据库操作失败',
        self::ERR_INVALID_CREDENTIAL => '用户名密码错误',
        self::ERR_INVALID_USER       => '用户不存在',
        self::ERR_SEND_EMAIL_FAIL    => '邮件发送失败',
        self::ERR_VERIFIED_ONLY      => '邮箱或手机还没验证',
        self::ERR_CSRF               => 'CSRF校验失败',
        self::ERR_NOT_LOGIN          => '用户未登录',
        self::ERR_NO_PERMISSION      => '没有权限',
    ];

    /**
     * 设置错误码
     *
     * @param int         $code    错误码
     * @param string|null $message 错误消息
     */
    public static function setCode(int $code, string $message = null): void
    {
        if ($code === null) {
            throw new \InvalidArgumentException(__('给定的错误码不能为null'));
        }
        static::$code = $code;
        //设置错误消息
        static::$message = $message;
    }

    /**
     * 得到错误码
     *
     * @return int
     */
    public static function getCode(): int
    {
        return static::$code !== null ? static::$code : static::ERR_FAIL;
    }

    /**
     * 得到错误消息
     *
     * @param int|null $code 错误码，为空取默认值
     * @return string 错误消息
     */
    public static function getMessage(int $code = null): string
    {
        if (static::$message !== null) {
            //如果设置了错误消息，就返回自己设置的
            return static::$message;
        }
        //默认取当前错误码
        if ($code === null) {
            $code = self::getCode();
        }
        if ($code !== null && array_key_exists($code, static::$messages)) {
            return static::$messages[$code];
        }
        //当前错误码为空或者错误消息未定义
        return self::DEFAULT_MESSAGE;
    }
}
