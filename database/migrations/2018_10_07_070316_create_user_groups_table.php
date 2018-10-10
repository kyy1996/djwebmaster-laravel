<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('用户组名称');
            $table->string('description')->default('')->comment('用户组描述');
            $table->boolean('status')->default(true)->comment('用户组状态');
            $table->text('rules')->default('')->comment('用户组拥有的权限规则id，多个规则 , 隔开');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->comment = '用户组信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
    }
}
