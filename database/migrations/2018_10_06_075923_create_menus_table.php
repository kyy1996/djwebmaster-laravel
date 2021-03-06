<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module')->default('Admin')->comment('所属模块');
            $table->string('group')->default('main')->comment('菜单分组：main-页面左侧主菜单/user-右上角用户信息菜单/nav-主页导航菜单');
            $table->string('title')->comment('菜单标题');
            $table->string('url')->comment('菜单url');
            $table->unsignedInteger('parent_id')->nullable()->comment('上级菜单ID');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序ID，越小的越在前面');
            $table->boolean('hide')->default(false)->comment('是否隐藏');
            $table->string('description')->default('')->comment('菜单项描述');
            $table->string('icon_class')->default('fa fa-cogs')->comment('菜单图标class名');
            $table->boolean('status')->default(true)->comment('是否启用');
            $table->timestamps();
            $table->index('module');
            $table->index('parent_id');
            $table->index('group');
            $table->index('sort');
            $table->foreign('parent_id')->references('id')->on('menus')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->comment = '菜单信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
