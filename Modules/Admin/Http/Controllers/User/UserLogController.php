<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Model\UserLog;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

class UserLogController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return $this->response($this->getPaginateResponse(UserLog::orderBy('updated_at', 'DESC')->paginate($this->pageSize)));
    }

    /**
     * Display the specified resource.
     *
     * @param  UserLog $log
     * @return \Illuminate\Http\Response
     */
    public function show(UserLog $log): Response
    {
        return $this->response($log);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UserLog $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserLog $log): Response
    {
        return $this->response($log->delete());
    }
}
