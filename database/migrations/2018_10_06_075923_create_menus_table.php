<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('module')->default('Web')->comment('所属模块');
            $table->string('title')->comment('菜单标题');
            $table->unsignedInteger('parent_id')->nullable()->comment('上级菜单ID');
            $table->unsignedTinyInteger('type')->comment('类型：1-url/2-主菜单');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序ID，越小的越在前面');
            $table->boolean('hide')->default(false)->comment('是否隐藏');
            $table->string('description')->default('')->comment('菜单项描述');
            $table->string('icon_class')->default('fa-cogs')->comment('菜单图标class名');
            $table->boolean('status')->default(true)->comment('是否启用');
            $table->timestamps();
            $table->softDeletes()->comment('软删除时间');
            $table->index('module');
            $table->index('parent_id');
            $table->index('type');
            $table->index('sort');
            $table->foreign('parent_id')->references('id')->on('menus')
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
        Schema::dropIfExists('menus');
    }
}
