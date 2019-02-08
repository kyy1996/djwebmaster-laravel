<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable()->comment('用户UID');
            $table->string('title')->default('')->comment('行为标题');
            $table->string('description', 500)->comment('行为描述，可以是markdown');
            $table->nullableMorphs('loggable');
            $table->boolean('result')->default(true)->comment('行为执行结果：0-失败/1-成功');
            $table->json('extra')->default('{}')->comment('额外信息，相关URL/相关附件ID/相关文章ID/相关活动、职位/执行结果/失败原因等');
            $table->ipAddress('ip')->nullable()->comment('操作人IP');
            $table->string('ua')->default('')->comment('操作人User-Agent');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('uid');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->comment = '用户操作日志表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
}
