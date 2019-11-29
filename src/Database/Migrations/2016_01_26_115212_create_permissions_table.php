<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = config('roles.permissionsTable');
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name', 64);
                $table->string('slug', 64)->unique();
                $table->string('description', 128)->nullable();
                $table->string('model', 64)->nullable();
                $table->uuid('parent_id')->index()->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::connection($connection)->table($table, function (Blueprint $table) {
                $table->foreign('parent_id')->references('id')->on($table)->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('roles.connection');
        $table = config('roles.permissionsTable');
        Schema::connection($connection)->dropIfExists($table);
    }
}
