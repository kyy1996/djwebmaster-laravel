<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户UID');
            $table->string('description')->comment('行为描述');
            $table->nullableMorphs('loggable');
            $table->timestamps();
            $table->index('uid');
            $table->foreign('uid')->references('uid')->on('users')
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
        Schema::dropIfExists('user_logs');
    }
}
