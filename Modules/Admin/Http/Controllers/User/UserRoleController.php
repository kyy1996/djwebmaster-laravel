<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Model\Permission;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

/**
 * 用户与角色关联控制器
 * Class UserRoleController
 *
 * @package Modules\Admin\Http\Controllers\User
 */
class UserRoleController extends AdminController
{
    protected static $rules = [
        'getUserRoles'            => [
            'uid' => 'required|integer|min:1',
        ],
        'postAddPermissionToRole' => [
            'role_id'        => 'required|integer|min:1',
            'permission_ids' => 'required|array|min:1',
        ],
        'postAddRoleToUser'       => [
            'uid'      => 'required|integer|min:1',
            'role_ids' => 'required|array|min:1',
        ],
        'postAddPermissionToUser' => [
            'uid'            => 'required|integer|min:1',
            'permission_ids' => 'required|array|min:1',
        ],
    ];

    /**
     * 得到用户角色与权限
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getGetUserRoles(Request $request): Response
    {
        $this->checkValidate($request->all(), 'getUserRoles');
        $uid  = $request->input('uid');
        $user = User::with(['roles.permissions', 'permissions'])->findOrFail($uid);
        return $this->response($user);
    }

    /**
     * 向角色添加权限
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postAddPermissionToRole(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['permission_ids'])) {
            $data['permission_ids'] = array_filter($data['permission_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddPermissionToRole');
        $role        = Role::with('permissions')->findOrFail($data['role_id']);
        $permissions = Permission::findMany($data['permission_ids']);
        if ($permissions && $permissions->isNotEmpty()) {
            $role->givePermissionTo($permissions);
        }
        return $this->response($role);
    }

    /**
     * 向用户添加角色
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postAddRoleToUser(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['role_ids'])) {
            $data['role_ids'] = array_filter($data['role_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddRoleToUser');
        $user  = User::with(['roles', 'permissions'])->findOrFail($data['uid']);
        $roles = Role::with('permissions')->findMany($data['role_ids']);
        if ($roles && $roles->isNotEmpty()) {
            $user->assignRole($roles)->load('roles.permissions');
        }
        return $this->response($user);
    }

    /**
     * 向用户添加权限
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postAddPermissionToUser(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['permission_ids'])) {
            $data['permission_ids'] = array_filter($data['permission_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddPermissionToUser');
        $user        = User::with('roles.permissions', 'permissions')->findOrFail($data['uid']);
        $permissions = Permission::findMany($data['permission_ids']);
        if ($permissions && $permissions->isNotEmpty()) {
            $user->givePermissionTo($permissions);
        }
        return $this->response($user);
    }

    /**
     * 与角色同步权限
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postSyncPermissionToRole(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['permission_ids'])) {
            $data['permission_ids'] = array_filter($data['permission_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddPermissionToRole');
        $role        = Role::with('permissions')->findOrFail($data['role_id']);
        $permissions = Permission::findMany($data['permission_ids']);
        if ($permissions && $permissions->isNotEmpty()) {
            $role->syncPermissions($permissions);
        }
        return $this->response($role);
    }

    /**
     * 与用户同步角色
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postSyncRoleToUser(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['role_ids'])) {
            $data['role_ids'] = array_filter($data['role_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddRoleToUser');
        $user  = User::with(['roles.permissions', 'permissions'])->findOrFail($data['uid']);
        $roles = Role::with('permissions')->findMany($data['role_ids']);
        if ($roles && $roles->isNotEmpty()) {
            $user->syncRoles($roles)->load('roles.permissions');
        }
        return $this->response($user);
    }

    /**
     * 与用户同步权限
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postSyncPermissionToUser(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['permission_ids'])) {
            $data['permission_ids'] = array_filter($data['permission_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddPermissionToUser');
        $user        = User::with('roles.permissions', 'permissions')->findOrFail($data['uid']);
        $permissions = Permission::findMany($data['permission_ids']);
        if ($permissions && $permissions->isNotEmpty()) {
            $user->syncPermissions($permissions);
        }
        return $this->response($user);
    }

    /**
     * 删除角色的权限
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postDeletePermissionFromRole(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['permission_ids'])) {
            $data['permission_ids'] = array_filter($data['permission_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddPermissionToRole');
        $role        = Role::with('permissions')->findOrFail($data['role_id']);
        $permissions = Permission::findMany($data['permission_ids']);
        if ($permissions && $permissions->isNotEmpty()) {
            $role->revokePermissionTo($permissions);
        }
        return $this->response($role);
    }

    /**
     * 删除用户所属角色
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postDeleteRoleFromUser(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['role_ids'])) {
            $data['role_ids'] = array_filter($data['role_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddRoleToUser');
        $user = User::with(['roles.permissions', 'permissions'])->findOrFail($data['uid']);
        $user->roles()->detach($data['role_ids']);
        return $this->response($user);
    }

    /**
     * 向用户删除权限
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postDeletePermissionFromUser(Request $request): Response
    {
        $data = $request->all();
        if (isset($data['permission_ids'])) {
            $data['permission_ids'] = array_filter($data['permission_ids'], function ($value) {
                return $value && is_numeric($value);
            });
        }
        $this->checkValidate($data, 'postAddPermissionToUser');
        $user        = User::with('roles.permissions', 'permissions')->findOrFail($data['uid']);
        $permissions = Permission::findMany($data['permission_ids']);
        if ($permissions && $permissions->isNotEmpty()) {
            $user->revokePermissionTo($permissions);
        }
        return $this->response($user);
    }
}
