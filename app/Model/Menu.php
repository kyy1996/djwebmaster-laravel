<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\Menu
 *
 * @property int                                                     $id
 * @property string                                                  $module      所属模块
 * @property string                                                  $group       菜单分组：main-页面左侧主菜单/user-右上角用户信息菜单/nav-主页导航菜单
 * @property string                                                  $title       菜单标题
 * @property int|null                                                $parent_id   上级菜单ID
 * @property int                                                     $type        类型：1-url/2-主菜单
 * @property int                                                     $sort        排序ID，越小的越在前面
 * @property int                                                     $hide        是否隐藏
 * @property string                                                  $description 菜单项描述
 * @property string                                                  $icon_class  菜单图标class名
 * @property int                                                     $status      是否启用
 * @property \Illuminate\Support\Carbon|null                         $created_at
 * @property \Illuminate\Support\Carbon|null                         $updated_at
 * @property \Illuminate\Support\Carbon|null                         $deleted_at  软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereIconClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\Menu|null                               $parent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Menu onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Menu withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Menu withoutTrashed()
 */
class Menu extends Model
{
    use SoftDeletes;

    protected $casts = [
        'hide'   => 'boolean',
        'status' => 'boolean'
    ];

    /**
     * 上级菜单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

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
