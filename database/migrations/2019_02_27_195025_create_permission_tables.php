<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames  = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('权限节点标识符，可以是：控制器/方法')->unique();
            $table->string('guard_name');
            $table->timestamps();
            $table->string('title')->default('')->comment('权限节点名称');
            $table->string('module')->default('Admin')->comment('权限规则所属模块');
            $table->softDeletes()->comment("软删除，也用来表示角色状态，校验时候仅查询存在数据，修改时查询全部数据");
            $table->comment = '用户权限规则表';
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('')->comment('角色名称');
            $table->string('name')->unique()->comment('角色标识符');
            $table->string('module')->default('')->comment('角色所属模块');
            $table->string('guard_name');
            $table->timestamps();
            $table->string('description')->default('')->comment('角色描述');
            $table->boolean('status')->default(true)->comment('角色状态');
            $table->softDeletes()->comment("软删除，也用来表示角色状态，校验时候仅查询存在数据，修改时查询全部数据");
            $table->comment = '用户角色信息表';
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type',]);

            $table->foreign('permission_id')
                  ->references('id')
                  ->on($tableNames['permissions'])
                  ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_permissions_permission_model_type_primary');

            $table->comment = '用户与权限节点关系表';
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type',]);

            $table->foreign('role_id')
                  ->references('id')
                  ->on($tableNames['roles'])
                  ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_roles_role_model_type_primary');

            $table->comment = '用户与角色关系表';
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                  ->references('id')
                  ->on($tableNames['permissions'])
                  ->onDelete('cascade');

            $table->foreign('role_id')
                  ->references('id')
                  ->on($tableNames['roles'])
                  ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            app('cache')
                ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
                ->forget(config('permission.cache.key'));

            $table->comment = '角色与权限节点关系表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
