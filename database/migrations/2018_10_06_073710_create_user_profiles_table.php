<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->unsignedInteger('uid');
            $table->string('stu_no', 12)->default('')->comment('学号');
            $table->string('school')->default('')->comment('学院');
            $table->string('class')->default('')->comment('班级');
            $table->string('name')->default('')->comment('姓名');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('stu_no');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->comment = '用户详细信息表';

            $table->primary('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
