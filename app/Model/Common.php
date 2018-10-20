<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Common
 *
 * @property string                                                  $name  配置项名称
 * @property string                                                  $type  配置项类型：text-文本/file-文件
 * @property string                                                  $group 配置项分组：common-通用/seo-SEO
 * @property string                                                  $value 配置值
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Common whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Common whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Common whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Common whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Common whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Common whereValue($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @mixin \Eloquent
 */
class Common extends Model
{
    protected $fillable = [
        'name', 'type', 'group', 'value'
    ];

    /**
     * 相关记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs()
    {
        return $this->morphMany(UserLog::class, 'loggable');
    }
}
