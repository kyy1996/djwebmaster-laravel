<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-27
 * Time: 19:57
 */

namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @package App\Model
 */
class Role extends \Spatie\Permission\Models\Role
{
    use SoftDeletes;

    public $modelName = '用户角色';

    public $guarded = ['*'];

    protected $fillable = [
        'description', 'status', 'name', 'title', 'id', 'guard_name', 'module',
    ];

    const STATUS_ALL     = -1;
    const STATUS_VALID   = 1;
    const STATUS_INVALID = 0;

    public static function create(array $attributes = []): Role
    {
        return parent::create($attributes);
    }
}
