<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable()->comment('发表用户UID');
            $table->string('title')->comment('文章标题');
            $table->longText('content')->nullable()->comment('文章内容');
            $table->string('cover_img')->default('')->comment('封面图片URL');
            $table->json('tags')->comment('文章标签JSON数组');
            $table->boolean('hide')->default(false)->comment('是否隐藏');
            $table->unsignedSmallInteger('read_count')->default(0)->comment('阅读次数');
            $table->unsignedSmallInteger('comment_count')->default(0)->comment('评论数量');
            $table->json('extra')->comment('文章额外信息JSON对象，可以扩展附件，活动ID，职位ID……');
            $table->ipAddress('ip')->nullable()->comment('发表人IP');
            $table->softDeletes()->comment('软删除时间');
            $table->timestamps();
            $table->index('uid');
            $table->index('hide');
            $table->index('title');
            $table->index(['title', 'hide']);
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->comment = '文章信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
