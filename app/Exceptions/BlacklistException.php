<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-28
 * Time: 12:35
 */

namespace App\Exceptions;


use Illuminate\Auth\AuthenticationException;

/**
 * 被拉黑时抛异常
 * Class BlacklistException
 *
 * @package App\Exceptions
 */
class BlacklistException extends AuthenticationException
{
    /**
     * Create a new authentication exception.
     *
     * @param  string      $message
     * @param  array       $guards
     * @param  string|null $redirectTo
     * @return void
     */
    public function __construct($message = 'Blacklisted.', array $guards = [], $redirectTo = null)
    {
        parent::__construct($message, $guards, $redirectTo);
    }
}
