<?php

namespace Modules\Admin\Http\Controllers\Common;

use App\Model\Code;
use App\Model\Menu;
use App\Model\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Modules\Admin\Http\Controllers\AdminController;

/**
 * 菜单管理
 * Class MenuController
 *
 * @package Modules\Admin\Http\Controllers\Common
 */
class MenuController extends AdminController
{
    protected static $rules;

    public function __construct()
    {
        parent::__construct();
        static::$rules = [
            'getIndex'   => [
                'module'    => 'nullable|string|in:Admin,Web',
                'parent_id' => 'nullable|integer|min:-1',
            ],
            'getShow'    => [
                'id' => 'required|integer|min:1',
            ],
            'postUpdate' => [
                'id'         => 'nullable|integer|min:1',
                'module'     => [
                    'nullable',
                    'string',
                    Rule::in(array_keys(Module::MODULE)),
                ],
                'group'      => 'nullable|string',
                'title'      => 'required|string',
                'url'        => [
                    Rule::requiredIf(function () {
                        return \request()->input('parent_id');
                    }),
                ],
                'parent_id'  => 'nullable|integer|min:0',
                'sort'       => 'nullable|integer|min:0',
                'hide'       => 'nullable|boolean',
                'icon_class' => 'nullable|string',
                'status'     => 'nullable|boolean',
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
        $this->checkValidate($request->all(), __FUNCTION__);
        $module   = $request->input('module');
        $parentId = $request->input('parent_id');
        $model    = new Menu();
        if ($module !== null) {
            $model->where('module', $module);
        }
        $parentInfo = null;
        if ($parentId !== null && $parentId > -1) {
            if ($parentId == 0) {
                //顶级菜单，parent_id保存的是null
                $model->whereNull('parent_id');
            } else {
                $model->where('parent_id', $parentId);
                $parentInfo = Menu::find($parentId);
            }
        }
        $response                = $this->getPaginateResponse($model->paginate($this->pageSize));
        $response['module_list'] = $this->convertToIdMenu(Module::MODULE);
        $response['parent']      = $parentInfo;
        $response['menu_list']   = Menu::getNestedTitleMenuList();
        return $this->response($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        $menu                = Menu::with('parent', 'children')->findOrFail($request->input('id'))->toArray();
        $menu['module_list'] = $this->convertToIdMenu(Module::MODULE);
        $menu['menu_list']   = Menu::getNestedTitleMenuList();
        return $this->response($menu);
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
        $this->checkValidate($request->all(), __FUNCTION__);
        $id = $request->input('id');
        if ($id && $id > 0) {
            $menu = Menu::with('parent')->findOrFail($id);
        } else {
            $menu = new Menu();
        }
        $parentId = $request->input('parent_id');
        if ($parentId == 0) $parentId = null;
        if ($parentId !== null) {
            $parentMenu = Menu::findOrFail($parentId);
            $menu->parent()->associate($parentMenu);
        } else if ($menu->parent) {
            $menu->parent()->dissociate();
        }
        $request->input('module') !== null && $menu->module = $request->input('module');
        $request->input('group') !== null && $menu->group = $request->input('group');
        $request->input('title') !== null && $menu->title = trim($request->input('title'));
        $menu->url = trim($request->input('url') ?: '');
        $request->input('description') !== null && $menu->description = $request->input('description');
        $request->input('sort') !== null && $menu->sort = !!$request->input('sort');
        $request->input('hide') !== null && $menu->hide = !!$request->input('hide');
        $request->input('status') !== null && $menu->status = !!$request->input('status');
        $menu->icon_class = $request->input('icon_class') ?: '';
        $menu->saveOrFail();
        return $this->response($menu);
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
        $this->checkValidate($request->all(), __FUNCTION__);
        $menu = Menu::findOrFail($request->input('id'));
        $ret  = $menu->delete();
        if (!$ret) {
            Code::setCode(Code::ERR_DB_FAIL);
        }
        return $this->response();
    }
}
