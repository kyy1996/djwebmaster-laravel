<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Model\Code;
use App\Model\UserLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

class UserLogController extends AdminController
{
    protected static $rules = [
        'getIndex'     => [],
        'deleteDelete' => [
            'id' => 'required|integer|min:1',
        ],
        'getShow'      => [
            'id' => 'required|integer|min:1',
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
        $this->checkValidate($request->all(), 'getIndex');
        return $this->response($this->getPaginateResponse(UserLog::orderBy('updated_at', 'DESC')->paginate($this->pageSize)));
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
        $userLog = UserLog::findOrFail($request->input('id'));
        return $this->response($userLog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteDelete(Request $request): Response
    {
        $userLog = UserLog::findOrFail($request->input('id'));
        $ret     = $userLog->delete();
        if (!$ret) {
            Code::setCode(Code::ERR_DB_FAIL);
        }
        return $this->response($ret);
    }
}
