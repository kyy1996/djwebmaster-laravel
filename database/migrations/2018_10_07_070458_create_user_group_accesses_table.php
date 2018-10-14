<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserGroupAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_group_accesses', function (Blueprint $table) {
            $table->unsignedInteger('uid');
            $table->unsignedInteger('group_id');
            $table->unique(['uid', 'group_id']);
            $table->foreign('uid')->references('uid')->on('users')
                  ->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('group_id')->references('id')->on('user_groups')
                  ->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->comment = '用户与用户组关系表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_group_accesses');
    }
}
