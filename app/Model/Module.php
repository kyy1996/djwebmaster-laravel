<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-03-03
 * Time: 20:30
 */

namespace App\Model;


/**
 * 模块
 * Class Module
 *
 * @package App\Model
 */
class Module
{
    const MODULE_ADMIN = 'Admin';
    const MODULE_WEB   = 'Web';
    const MODULE       = [
        self::MODULE_ADMIN => 'Admin',
        self::MODULE_WEB   => 'Web',
    ];
}
