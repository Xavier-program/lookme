<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
            // Si existe la columna user_id
            if (Schema::hasColumn('codes', 'user_id')) {

                // Primero elimina la FK (si existe)
                $table->dropForeign(['user_id']);

                // Luego elimina la columna
                $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            // Si NO existe, la crea
            if (!Schema::hasColumn('codes', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }
};
