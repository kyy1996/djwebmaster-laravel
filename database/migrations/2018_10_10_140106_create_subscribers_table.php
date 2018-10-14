<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable()->comment('订阅用户UID');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('mobile')->default('')->comment('手机号');
            $table->string('scope')
                  ->comment('用户订阅信息范围：all-全部/article-博客文章/activity_new-新活动举办通知/activity_start-活动开始/score-成绩通知/job_new-新职位/job_result-职位申请反馈/comment-评论与回复通知')
                  ->default('');
            $table->boolean('valid')->default(true)->comment('是否有效，如果用户取消订阅则无效');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->comment = '用户资讯订阅信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribers');
    }
}
