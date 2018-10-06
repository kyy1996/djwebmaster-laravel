<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('uid');
            $table->string('avatar')->default('')->comment('用户头像');
            $table->string('mobile', 20)->unique()->comment('用户手机号');
            $table->string('password')->comment('加密后的用户密码');
            $table->string('email')->unique()->comment('用户邮箱');
            $table->boolean('admin')->default(false)->comment('是否是管理员：0-普通用户/1-管理员');
            $table->boolean('status')->default(true)->comment('账户状态：1-启用/0-停用');
            $table->timestamp('email_verified_at')->nullable()->comment('邮箱验证时间，为空表示邮箱还未被验证');
            $table->timestamp('mobile_verified_at')->nullable()->comment('手机号验证时间，为空表示手机还未被验证');
            $table->rememberToken()->comment('记住密码TOKEN，即自动登录TOKEN，Token携带有效期');
            $table->ipAddress('create_ip')->nullable()->comment('注册IP');
            $table->ipAddress('update_ip')->nullable()->comment('更新IP');
            $table->timestamp('last_login_at')->nullable()->comment('上次登录时间');
            $table->ipAddress('last_login_ip')->nullable()->comment('上次登录IP');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('status');
            $table->index('admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
