<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-05
 * Time: 22:02
 */

namespace Modules\Admin\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

class WelcomeController extends AdminController
{
    public function index(Request $request): Response
    {
        return $this->getIndex($request);
    }

    public function getIndex(Request $request): Response
    {
        $data           = $request->all();
        $data['module'] = config('web.name');
        return $this->response($data);
    }
}
