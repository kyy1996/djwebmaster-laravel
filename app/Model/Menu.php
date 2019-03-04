<?php

namespace App\Model;

use App\Common\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Model\Menu
 *
 * @property int                                                     $id
 * @property string                                                  $module      所属模块
 * @property string                                                  $group
 *           菜单分组：main-页面左侧主菜单/user-右上角用户信息菜单/nav-主页导航菜单
 * @property string                                                  $title       菜单标题
 * @property int|null                                                $parent_id   上级菜单ID
 * @property string                                                  $url         菜单url
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
 * @mixin \Illuminate\Database\Query\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|UserLog[] $logs
 * @property-read \App\Model\Menu|null                               $parent
 */
class Menu extends Model
{
    public $modelName = '菜单';

    protected $fillable = [
        'module', 'group', 'title', 'type', 'sort', 'hide', 'description', 'icon_class', 'status',
    ];

    protected $casts = [
        'hide'   => 'boolean',
        'status' => 'boolean',
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
     * 下级菜单
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
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

    /**
     * 得到树形目录
     *
     * @param \Closure $query
     * @return array
     */
    public static function getMenuTree($query = null): array
    {
        $model = static::orderBy('id', 'asc')->orderBy('sort', 'asc');
        if ($query && $query instanceof \Closure) {
            $model->where($query);
        }
        $list = $model->get();
        $tree = Util::list2tree($list, 'id', 'parent_id', '_child', null) ?: [];
        $tree = array_values($tree);
        return $tree;
    }

    /**
     * 得到体现层级关系菜单名的菜单列表
     *
     * @param \Closure|null $query     查询条件
     * @param int           $level     保留层级数量
     * @param string        $delimiter 连接符
     * @return array
     */
    public static function getNestedTitleMenuList($query = null, int $level = 1, string $delimiter = ' - '): array
    {
        $menuTree = Menu::getMenuTree($query);
        Util::convertNestedTitleTree($menuTree, $delimiter);
        return Util::tree2list($menuTree, $level);
    }

    /**
     * 隶属于这个菜单的权限节点
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'menu_id');
    }

    /**
     * 根据用户拥有的权限，得到菜单
     *
     * @param \Spatie\Permission\Traits\HasRoles|null $user
     * @param string                                  $group 获取哪个分组的菜单
     * @param null|\Closure                           $query
     * @return \Illuminate\Support\Collection
     */
    public static function getMenuForUser($user, $group = '', $query = null): Collection
    {
        $model = static::with('permissions');
        ($query instanceof \Closure) && $model->where($query);
        $group && $model->whereGroup($group);
        $menus = $model->get();
        if (!$user) return $menus;
        $result = collect();
        /** @var \App\Model\Menu $menu */
        foreach ($menus as $menu) {
            if ($menu->permissions instanceof Collection) {
                if ($menu->permissions->isNotEmpty()) {
                    if (!$user->hasAnyPermission($menu->permissions->all())) {
                        //菜单下的权限节点不为空，并且用户没有拥有其中任何一个权限
                        continue;
                    }
                }
            }
            $result->push($menu);
        }
        return $result;
    }
}
