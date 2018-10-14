<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_id')->comment('签到的活动ID');
            $table->unsignedInteger('uid')->nullable()->comment('签到用户UID');
            $table->string('stu_no', 12)->default('')->comment('学号');
            $table->string('school')->default('')->comment('学院');
            $table->string('class')->default('')->comment('班级');
            $table->string('name')->default('')->comment('姓名');
            $table->boolean('valid')->default(true)->comment('是否有效');
            $table->text('comment')->default('')->comment('备注');
            $table->ipAddress('ip')->nullable()->comment('签到时用的IP');
            $table->string('ua')->default('')->comment('签到时所用浏览器User-Agent');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');

            $table->index('activity_id');
            $table->index('uid');
            $table->index('stu_no');
            $table->foreign('activity_id')->references('id')->on('activities')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->comment = '活动签到表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkins');
    }
}
