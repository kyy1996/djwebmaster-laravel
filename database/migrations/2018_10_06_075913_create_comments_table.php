<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable()->comment('发表人用户UID');
            $table->unsignedInteger('parent_id')->nullable()->comment('父评论ID，也就是被回复的评论ID');
            $table->string('nickname')->comment('发表用户昵称');
            $table->string('email')->comment('发表人邮箱');
            $table->longText('content')->comment('内容');
            $table->ipAddress('ip')->comment('发表人IP');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('uid');
            $table->index('email');
            $table->index('parent_id');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('parent_id')->references('id')->on('comments')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->comment = '站点留言/回复信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
