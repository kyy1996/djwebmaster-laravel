<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Model\Blacklist;
use App\Model\Code;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Controllers\AdminController;

class BlacklistController extends AdminController
{

    protected static $rules = [
        'default'      => [
            'id'           => 'nullable|integer|min:1',
            'uid'          => 'required|integer|min:1|exists:users,uid',
            'stu_no'       => 'nullable|integer|min:1',
            'comment'      => 'nullable|string|max:255',
            'valid'        => 'boolean',
            'with_trashed' => 'boolean',
        ],
        'getIndex'     => [
            'uid' => 'nullable|integer|min:1|exists:users,uid',
        ],
        'getShow'      => [
            'id'  => 'required|integer|min:1|exists:blacklists,id',
            'uid' => 'nullable|integer|min:1|exists:users,uid',
        ],
        'deleteDelete' => [
            'id'  => 'required|integer|min:1|exists:blacklists,id',
            'uid' => 'nullable|integer|min:1|exists:users,uid',
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
        $blacklist = new Blacklist();
        if ($request->input('with_trashed')) {
            $blacklist = $blacklist->withTrashed();
        }
        if ($request->input('valid') !== null) {
            $blacklist->where('valid', $request->input('valid'));
        }
        if ($request->input('stu_no') !== null) {
            $blacklist->where('stu_no', $request->input('stu_no'));
        }
        $pagination = $this->getPaginateResponse($blacklist->paginate($this->pageSize));
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
        $data = $request->all();
        $this->checkValidate($data, 'postUpdate');
        $uid       = $request->input('uid');
        $blacklist = Blacklist::withTrashed()->whereUid($uid)->first();
        if (!$blacklist) {
            $id        = $request->input('id') ?: 0;
            $blacklist = $id > 0 ? Blacklist::withTrashed()->findOrFail(+$id) : new Blacklist();
        }
        unset($data['id']);
        $data['comment'] = $data['comment'] ?: '';
        $data['valid']   = !!$data['valid'];
        $blacklist->fill($data);
        $blacklist->operator_uid = Auth::id();
        $blacklist->restore();
        $blacklist->saveOrFail();
        return $this->response($blacklist);
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
        $id        = +$request->input('id');
        $blacklist = Blacklist::findOrFail($id);
        return $this->response($blacklist);
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
        $id        = +$request->input('id');
        $blacklist = Blacklist::findOrFail($id);
        $ret       = $blacklist->delete();
        if (!$ret) {
            Code::setCode(Code::ERR_DB_FAIL);
        }
        return $this->response();
    }
}
