<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-27
 * Time: 19:56
 */

namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Permission
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @package App\Model
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    use SoftDeletes;

    public $modelName = '权限节点';

    /**
     * 权限所属菜单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public static function create(array $attributes = []): Permission
    {
        return parent::create($attributes);
    }
}
