<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlacklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable()->unique()->comment('被拉黑的用户UID');
            $table->string('stu_no')->nullable()->unique()->comment('学号');
            $table->string('comment')->default('')->comment('备注');
            $table->boolean('valid')->default(true)->comment('是否有效');
            $table->unsignedInteger('operator_uid')->comment('操作用户UID');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('operator_uid');
            $table->index('valid');
            $table->foreign('operator_uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->comment = '黑名单用户信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blacklists');
    }
}
