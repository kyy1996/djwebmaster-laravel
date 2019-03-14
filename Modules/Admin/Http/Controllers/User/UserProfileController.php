<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Common\Util;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Admin\Http\Controllers\AdminController;

class UserProfileController extends AdminController
{
    protected static $rules;

    public function __construct()
    {
        parent::__construct();
        static::$rules = [
            'default'      => [
                'pageIndex' => 'integer|min:1',
                'pageSize'  => 'integer|min:1',
            ],
            'postUpdate'   => [
                'email'    => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore(\request()->input('uid'), 'uid'),
                ],
                'password' => 'nullable|string|min:6',
                'mobile'   => [
                    'nullable',
                    'string',
                    'max:15',
                    Rule::unique('users')->ignore(\request()->input('uid'), 'uid'),
                ],
                'avatar'   =>
                    [
                        'nullable',
                        'string',
                        'regex:/^(http:|https:)?\/\/[^:\s]+$/',
                    ],
            ],
            'deleteDelete' => [
                'uid' => 'required|integer|min:1',
            ],
            'getShow'      => [
                'uid' => 'required|integer|min:1',
            ],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(): Response
    {
        $user = User::paginate($this->pageSize);
        return $this->response($this->getPaginateResponse($user));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postUpdate(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        $uid             = $request->input('uid');
        $user            = $uid > 0 ? User::findOrFail($uid) : new User();
        $user->mobile    = $request->input('mobile') ?: '';
        $user->avatar    = $request->input('avatar') ?: '';
        $user->admin     = $request->input('admin', false);
        $user->status    = $request->input('status', true);
        $user->update_ip = Util::getUserIp($request);
        $request->input('password') && $user->password = Hash::make($request->input('password'));
        if ($uid > 0) {
            $user->create_ip = Util::getUserIp($request);
        }
        $user->saveOrFail();
        !$user->profile && $user->profile()->create();
        $user->profile->stu_no = $request->input('stu_no') ?: '';
        $user->profile->class  = $request->input('class') ?: '';
        $user->profile->school = $request->input('school') ?: '';
        $user->profile->name   = $request->input('name') ?: '';
        $user->profile->saveOrFail();
        return $this->response($user);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request)
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        $user = User::findOrFail($request->input('uid'));
        return $this->response($user);
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
        $user = User::findOrFail($request->input('uid'));
        $user->profile()->delete();
        $user->delete();
        return $this->response();
    }
}
