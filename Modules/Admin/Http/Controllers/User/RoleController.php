<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Model\Code;
use App\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

/**
 * 角色控制器
 * Class RoleController
 *
 * @package Modules\Admin\Http\Controllers\User
 */
class RoleController extends AdminController
{
    protected static $rules = [
        'default'      => [
            'status' => 'nullable|in:-1,0,1',
        ],
        'getShow'      => [
            'id' => 'required|integer|min:1|exists:roles,id',
        ],
        'postUpdate'   => [
            'name'        => 'required|string|min:1',
            'id'          => 'nullable|integer|min:1',
            'description' => 'nullable|integer|min:1',
        ],
        'deleteDelete' => [
            'id' => 'required|integer|min:1|exists:roles,id',
        ],
    ];

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
        $data = $request->only('description', 'status', 'name', 'id');
        $this->checkValidate($data, 'postUpdate');
        $id                = $request->input('id') ?: 0;
        $role              = $id > 0 ? Role::with('permissions')->withTrashed()->findOrFail(+$id) : new Role();
        $role->name        = $data['name'];
        $role->description = @$data['description'] ?: '';
        @$data['status'] !== null && $role->status = !!$data['status'];
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
}
