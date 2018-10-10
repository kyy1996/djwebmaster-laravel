<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('职位名称');
            $table->string('department')->comment('部门');
            $table->text('description')->comment('职位描述');
            $table->unsignedInteger('article_id')->nullable()->comment('关联文章ID');
            $table->boolean('status')->default(true)->comment('职位状态');
            $table->unsignedInteger('uid')->nullable()->comment('发布人UID');
            $table->ipAddress('ip')->nullable()->comment('发布人IP');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('article_id');
            $table->index('department');
            $table->index('uid');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('article_id')->references('id')->on('articles')
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
        Schema::dropIfExists('jobs');
    }
}
