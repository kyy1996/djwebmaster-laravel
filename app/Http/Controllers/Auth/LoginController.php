<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppController;
use App\Model\Code;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * |--------------------------------------------------------------------------
 * | Login Controller
 * |--------------------------------------------------------------------------
 * |
 * | This controller handles authenticating users for the application and
 * | redirecting them to your home screen. The controller uses a trait
 * | to conveniently provide its functionality to your applications.
 * |
 */
class LoginController extends AppController
{

    use AuthenticatesUsers;

    protected static $rules = [
        'default' => [
            'email'    => 'required|string',
            'password' => 'required|string',
        ],
    ];

    /**
     * Where to redirect users after login.
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
//        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(): Response
    {
        return $this->response(Auth::user());
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->checkValidate($request->all());
    }

    /**
     * 登录成功
     *
     * @param \Illuminate\Http\Request         $request
     * @param \Illuminate\Foundation\Auth\User $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, User $user): Response
    {
        return $this->response($user);
    }

    /**
     * 登录失败
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request): Response
    {
        Code::setCode(Code::ERR_INVALID_CREDENTIAL);
        return $this->response();
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request): Response
    {
        return $this->response();
    }

}
