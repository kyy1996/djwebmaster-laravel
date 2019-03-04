<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Model\Code;
use App\Model\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Modules\Admin\Http\Controllers\AdminController;

/**
 * 用户订阅管理控制器
 * Class SubscriberController
 *
 * @package Modules\Admin\Http\Controllers\User
 */
class SubscriberController extends AdminController
{

    protected static $rules;

    public function __construct()
    {
        parent::__construct();

        static::$rules = [
            'getIndex'     => [
                'scope' => 'nullable|integer|min:0',
            ],
            'getShow'      => [
                'id' => 'required|integer|min:1',
            ],
            'postUpdate'   => [
                'id'     => 'nullable|integer|min:1',
                'uid'    => [
                    'nullable',
                    'integer',
                    'min:1',
                    Rule::unique('subscriber')->ignore(\request()->input('id')),
                    Rule::exists('users', 'uid'),
                ],
                'email'  => 'required|email',
                'mobile' => [
                    'nullable',
                    'string',
                    'regex:/(\+\d{1,3})?\d{9,}/',
                ],
                'scope'  => 'required_without:id|integer|min:0',
                'valid'  => 'required_without:id|boolean',
            ],
            'deleteDelete' => [
                'id' => 'required|integer|min:1',
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
        $scope      = $request->input('scope');
        $subscriber = new Subscriber();
        if ($scope !== null && $scope > 0) {
            //取订阅内容交集，因为scope是二进制
            $subscriber->where('scope', '&', $scope);
        }
        $paginate = $subscriber->paginate($this->pageSize);
        $response = $this->getPaginateResponse($paginate);
        //可订阅的内容菜单
        $response['scope_list'] = $this->convertToIdMenu(Subscriber::SCOPE);
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
        $id   = +$request->input('id');
        $data = Subscriber::findOrFail($id);
        return $this->response($data);
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
        if ($id !== null) {
            $model = Subscriber::findOrFail($id);
        } else {
            $model = new Subscriber();
        }
        $request->input('email') !== null && $model->email = $request->input('email');
        $request->input('mobile') !== null && $model->mobile = $request->input('mobile');
        $request->input('scope') !== null && $model->scope = +$request->input('scope');
        $request->input('valid') !== null && $model->valid = !!$request->input('valid');
        $request->input('uid') !== null && $model->uid = $request->input('uid');
        if ($model->valid === null) {
            $model->valid = 1;
        }
        if ($model->scope === null) {
            $model->scope = Subscriber::SCOPE_ALL;
        }
        $model->saveOrFail();
        return $this->response($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function deleteDelete(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        $id    = $request->input('id');
        $model = Subscriber::findOrFail($id);
        $ret   = $model->delete();
        if (!$ret) Code::setCode(Code::ERR_DB_FAIL);
        return $this->response();
    }
}
