<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Common
 *
 * @property string $name 配置项名称
 * @property string $type 配置项类型：text-文本/file-文件
 * @property string $value 配置值
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Common whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Common whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Common whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Common whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Common whereValue($value)
 * @mixin \Eloquent
 */
class Common extends Model
{
    //
}
