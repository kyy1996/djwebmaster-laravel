<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_id')->comment('报名的活动ID');
            $table->unsignedInteger('uid')->nullable()->comment('报名用户UID');
            $table->string('stu_no', 12)->default('')->comment('学号');
            $table->string('school')->default('')->comment('学院');
            $table->string('class')->default('')->comment('班级');
            $table->string('name')->default('')->comment('姓名');
            $table->string('qq', 20)->default('')->comment('联系QQ');
            $table->string('mobile', 20)->default('')->comment('通知手机号');
            $table->string('email')->default('')->comment('通知邮箱');
            $table->boolean('valid')->default(true)->comment('是否有效');
            $table->text('comment')->default('')->comment('报名说明');
            $table->ipAddress('ip')->nullable()->comment('报名时用的IP');
            $table->string('ua')->default('')->comment('报名时所用浏览器User-Agent');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');

            $table->index('activity_id');
            $table->index('uid');
            $table->index('stu_no');
            $table->foreign('activity_id')->references('id')->on('activities')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signups');
    }
}
