<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserGroupRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_group_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module')->default('Admin')->comment('权限规则所属模块');
            $table->string('name')->comment('权限节点标识符，可以是：控制器/方法')->unique();
            $table->string('title')->default('')->comment('权限节点名称');
            $table->boolean('status')->default(true)->comment('是否启用');
            $table->timestamps();
            $table->comment = '用户权限规则表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_group_rules');
    }
}
