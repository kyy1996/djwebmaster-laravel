<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedInteger('uid')->nullable()->comment('被拉黑的用户UID');
            $table->string('stu_no')->comment('学号');
            $table->string('class')->default('')->comment('班级');
            $table->string('name')->default('姓名')->comment('姓名');
            $table->string('comment')->default('')->comment('备注');
            $table->boolean('valid')->default(true)->comment('是否有效');
            $table->unsignedInteger('operator_uid')->comment('操作用户UID');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('stu_no');
            $table->index('operator_uid');
            $table->index('valid');
            $table->foreign('operator_uid')->references('uid')->on('users')
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
        Schema::dropIfExists('blacklists');
    }
}
