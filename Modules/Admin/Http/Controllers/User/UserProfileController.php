<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Common\Util;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

class UserProfileController extends AdminController
{
    protected static $rules = [
        'default' => [
            'pageIndex' => 'integer|min:1',
            'pageSize'  => 'integer|min:1',
        ],
        'store'   => [
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'mobile'   => 'nullable|string|max:15|unique:users',
            'avatar'   =>
                [
                    'nullable',
                    'string',
                    'regex:/^(http:|https:)?\/\/[^:\s]+$/',
                ],
        ],
        'update'  => [
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'mobile'   => 'nullable|string|max:15|unique:users',
            'avatar'   =>
                [
                    'nullable',
                    'string',
                    'regex:/^(http:|https:)?\/\/[^:\s]+$/',
                ],
        ],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $user = User::paginate($this->pageSize);
        return $this->response($this->getPaginateResponse($user));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request): Response
    {
        $this->checkValidate($request->all(), 'store');
        $data = [
            'uid'       => $request->input('uid'),
            'mobile'    => $request->input('mobile'),
            'avatar'    => $request->input('avatar'),
            'admin'     => $request->input('admin', false),
            'status'    => $request->input('status', true),
            'create_ip' => Util::getUserIp($request),
            'update_ip' => Util::getUserIp($request),
        ];
        $user = new User($data);
        $user->saveOrFail();
        $user->profile()->create();
        return $this->response($user);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->response($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user): Response
    {
        return $this->response($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\Model\User           $user
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Request $request, User $user)
    {
        $this->checkValidate($request->all(), 'store');
        $data = [
            'mobile'    => $request->input('mobile'),
            'avatar'    => $request->input('avatar'),
            'admin'     => $request->input('admin', false),
            'status'    => $request->input('status', true),
            'update_ip' => Util::getUserIp($request),
        ];
        $user->fill($data);
        $user->saveOrFail();
        return $this->response($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user): Response
    {
        $user->profile()->delete();
        $user->delete();
        return $this->response();
    }
}
