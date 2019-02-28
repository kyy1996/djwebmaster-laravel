<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Model\Code;
use App\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Modules\Admin\Http\Controllers\AdminController;

/**
 * 角色管理控制器
 * Class RoleController
 *
 * @package Modules\Admin\Http\Controllers\User
 */
class RoleController extends AdminController
{
    protected static $rules;

    public function __construct()
    {
        parent::__construct();
        static::$rules = [
            'default'      => [
                'status' => 'nullable|in:-1,0,1',
            ],
            'getShow'      => [
                'id' => 'required|integer|min:1|exists:roles,id',
            ],
            'postUpdate'   => [
                'name'        => [
                    'required',
                    'string',
                    'min:1',
                    Rule::unique('roles')->ignore(\request()->input('id')),
                ],
                'title'       => 'nullable|string',
                'module'      => 'nullable|string',
                'id'          => 'nullable|integer|min:1',
                'description' => 'nullable|integer|min:1',
            ],
            'deleteDelete' => [
                'id' => 'required|integer|min:1|exists:roles,id',
            ],
            'getUsers'     => [
                'id' => 'required|integer|min:1|exists:roles,id',
            ],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request): Response
    {
        $data = $request->all();
        $this->checkValidate($data, 'getIndex');
        $role = Role::with('permissions');
        if ($request->input('with_trashed')) {
            $role = $role->withTrashed();
        }
        $status = $request->input('status', Role::STATUS_ALL);
        if ($status !== Role::STATUS_ALL) {
            $role->where('status', $status);
        }
        $pagination = $this->getPaginateResponse($role->paginate($this->pageSize));
        return $this->response($pagination);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postUpdate(Request $request): Response
    {
        $data = $request->only('description', 'status', 'name', 'title', 'module', 'id');
        $this->checkValidate($data, 'postUpdate');
        $id         = $request->input('id') ?: 0;
        $role       = $id > 0 ? Role::with('permissions')->withTrashed()->findOrFail(+$id) : new Role();
        $role->name = $data['name'];
        @$data['title'] !== null && $role->title = $data['title'];
        $role->description = @$data['description'] ?: '';
        @$data['status'] !== null && $role->status = !!$data['status'];
        @$data['module'] !== null && $role->module = $data['module'];
        $role->saveOrFail();
        $role->permissions;
        return $this->response($role);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request): Response
    {
        $this->checkValidate($request->all(), 'getShow');
        $id   = +$request->input('id');
        $role = Role::with('permissions')->findOrFail($id);
        return $this->response($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function deleteDelete(Request $request): Response
    {
        $this->checkValidate($request->all(), 'deleteDelete');
        $id   = +$request->input('id');
        $role = Role::findOrFail($id);
        $ret  = $role->delete();
        if (!$ret) {
            Code::setCode(Code::ERR_DB_FAIL);
        }
        return $this->response();
    }

    /**
     * 得到拥有该角色的用户
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request): Response
    {
        $this->checkValidate($request->all(), 'getUsers');
        $role              = Role::with('permissions')->findOrFail($request->input('id'));
        $users             = $this->getPaginateResponse($role->users()->without('roles')->paginate($this->pageSize));
        $data              = $users;
        $data['role_info'] = $role;
        return $this->response($data);
    }
}
