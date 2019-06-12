<?php

use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    protected $users = [
        [
            'mobile'   => '18181818181',
            'password' => '123456',
            'email'    => 'admin@admin.admin',
            'admin'    => 1,
            'avatar'   => '',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        foreach ($this->users as $userAttr) {
            if (User::whereEmail($userAttr['email'])->orWhere('mobile', $userAttr['mobile'])->first()) {
                continue;
            }
            $userAttr['password'] = Hash::make($userAttr['password']);
            $user                 = new User($userAttr);
            $user->create_ip      = '127.0.0.1';
            $user->update_ip      = '127.0.0.1';
            $user->created_at     = Carbon::now();
            $user->updated_at     = Carbon::now();
            $user->saveOrFail();
            $profile = $user->profile()->make();
            foreach ($userAttr as $key => $value) {
                $keys = $profile->getFillable();
                if (in_array($key, $keys, true)) {
                    $profile->setAttribute($key, $value);
                }
            }
            $profile->save();
        }
    }
}
