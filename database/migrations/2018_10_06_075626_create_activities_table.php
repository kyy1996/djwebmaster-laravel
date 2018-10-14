<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('活动名称');
            $table->string('type')->default('教学活动')->comment('活动类型：教学活动/游戏/面基/xxx');
            $table->string('location')->default('')->comment('活动举办地点');
            $table->string('host')->default('')->comment('活动主持人：孔元元，或者孔元元/王一帅，斜杠分割多个人');
            $table->string('time')->default('')->comment('活动举办时间：周三20:00');
            $table->text('comment')->default('')->comment('活动备注');
            $table->json('extra')->default('{}')->comment('额外信息：相关附件ID');
            $table->unsignedInteger('article_id')->nullable('')->comment('活动关联文章ID，可为空');
            $table->json('host_uids')->default('[]')->comment('活动主持人对应UID数组：[1,2]，因为一个活动可以有多个人来主持');
            $table->unsignedSmallInteger('availability')->default(50)->comment('活动可容纳人数');
            $table->unsignedSmallInteger('signup_amount')->default(0)->comment('活动报名人数，程序自动更新');
            $table->boolean('hide')->default(false)->comment('是否属于隐藏活动');
            $table->boolean('pause')->default(false)->comment('是否暂停该活动');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('name');
            $table->index('type');
            $table->index('host');
            $table->index('location');
            $table->foreign('article_id')->references('id')->on('articles')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->comment = '活动表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
