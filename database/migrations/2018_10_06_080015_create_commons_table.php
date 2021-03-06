<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commons', function (Blueprint $table) {
            $table->string('name')->unique()->comment('配置项名称');
            $table->string('type')->default('text')->comment('配置项类型：text-文本/file-文件');
            $table->string('group')->default('common')->comment('配置项分组：common-通用/seo-SEO');
            $table->string('value')->comment('配置值');
            $table->timestamps();
            $table->comment = '站点通用配置项';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commons');
    }
}
