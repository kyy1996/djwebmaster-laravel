<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppController;
use App\Model\Code;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends AppController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
}
