<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_id')->comment('申请的职位ID');
            $table->unsignedInteger('uid')->nullable()->comment('申请用户UID');
            $table->string('stu_no', 12)->default('')->comment('学号');
            $table->string('school')->default('')->comment('学院');
            $table->string('class')->default('')->comment('班级');
            $table->string('name')->default('')->comment('姓名');
            $table->text('resume')->nullable()->comment('申请简历');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：-1-已拒绝/0-申请中/1-已审核/2-已通过');
            $table->unsignedInteger('operator_uid')->nullable()->comment('操作人UID');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');

            $table->index('job_id');
            $table->index('uid');
            $table->index('stu_no');
            $table->index('operator_uid');
            $table->foreign('job_id')->references('id')->on('jobs')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('job_applications');
    }
}
