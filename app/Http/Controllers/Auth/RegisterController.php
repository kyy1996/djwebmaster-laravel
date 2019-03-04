<?php

namespace App\Http\Controllers\Auth;

use App\Common\Util;
use App\Http\Controllers\AppController;
use App\Model\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * |--------------------------------------------------------------------------
 * | Register Controller
 * |--------------------------------------------------------------------------
 * |
 * | This controller handles the registration of new users as well as their
 * | validation and creation. By default this controller uses a trait to
 * | provide this functionality without requiring any additional code.
 * |
 */
class RegisterController extends AppController
{

    use RegistersUsers;

    protected static $rules = [
        'default' => [
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
     * Where to redirect users after registration.
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
//        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, self::$rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    protected function create(array $data)
    {
        /** @var User $user */
        $user = User::create([
            'email'     => trim($data['email']),
            'password'  => Hash::make($data['password']),
            'avatar'    => $data['avatar'] ? trim($data['avatar']) : '',
            'mobile'    => $data['mobile'] ? trim($data['mobile']) : '',
            'create_ip' => Util::getUserIp(\request()),
            'update_ip' => Util::getUserIp(\request()),
        ]);
        $user->profile()->create();
        $user->profile;
        return $user;
    }

    /**
     * 注册成功
     *
     * @param \Illuminate\Http\Request         $request
     * @param \Illuminate\Foundation\Auth\User $user
     * @return \Illuminate\Http\Response
     */
    protected function registered(Request $request, \Illuminate\Foundation\Auth\User $user): Response
    {
        return $this->response($user);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->checkValidate($request->all());

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
