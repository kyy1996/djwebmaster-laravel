<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppController;
use App\Model\Code;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends AppController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * 发送重设密码链接
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $response
     * @return \Illuminate\Http\Response
     */
    protected function sendResetLinkResponse(Request $request, string $response): Response
    {
        switch ($response) {
            case Password::INVALID_USER:
                //用户不存在
                Code::setCode(Code::ERR_INVALID_USER);
                break;
            case Password::RESET_LINK_SENT:
                //邮件发送成功
                Code::setCode(Code::SUCCESS);
                break;
        }
        return $this->response();
    }

    /**
     * 发送重设密码链接失败
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $response
     * @return \Illuminate\Http\Response
     */
    protected function sendResetLinkFailedResponse(Request $request, string $response): Response
    {
        return $this->sendResetLinkResponse($request, $response);
    }
}
