<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable()->comment('上传用户UID');
            $table->string('filename')->comment('原始文件名');
            $table->string('storage')->comment('存储类型：local-本地');
            $table->string('path')->comment('文件存放绝对路径：URL或者绝对路径');
            $table->string('url')->comment('文件下载地址');
            $table->unsignedInteger('size')->default(0)->comment('附件大小（字节）');
            $table->string('md5', 32)->default('')->comment('文件MD5');
            $table->string('mime')->default('application/octet-stream')->comment('MIME类型');
            $table->string('extension')->default('')->comment('文件扩展名');
            $table->json('extra')->nullable()->comment('附件额外属性JSON对象，可以保存媒体长度，图片大小，引用数量等');
            $table->boolean('valid')->default(true)->comment('文件是否有效（文件读不到就无效）');
            $table->unsignedInteger('count')->default(1)->comment('附件被上传了几次');
            $table->timestamps();
            $table->softDeletes()->comment('记录被软删除的时间（文件不一定被删除）');
            $table->foreign('uid')->references('uid')->on('users')
                  ->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->comment = '上传的附件信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
