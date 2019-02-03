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

    public const SUCCESS       = 0;
    public const ERR_FAIL      = -1;
    public const ERR_URL_ERROR = -2;

    public const DEFAULT_MESSAGE = '未定义';

    private static $messages = [
        self::SUCCESS       => '成功',
        self::ERR_FAIL      => '失败',
        self::ERR_URL_ERROR => '没有找到请求的地址',
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
        self::$code = $code;
        //设置错误消息
        ($message !== null) && (static::$message = $message);
    }

    /**
     * 得到错误码
     *
     * @return int
     */
    public static function getCode(): int
    {
        return self::$code !== null ? static::$code : static::ERR_FAIL;
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
