<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->unsignedInteger('uid')->unique();
            $table->string('stu_no', 12)->default('')->comment('学号');
            $table->string('school')->default('')->comment('学院');
            $table->string('class')->default('')->comment('班级');
            $table->string('name')->default('')->comment('姓名');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('stu_no');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('cascade')->onDelete('cascade');
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
