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
            // Solo elimina la FK si existe
            if (Schema::hasColumn('codes', 'user_id')) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $foreignKeys = $sm->listTableForeignKeys('codes');

                foreach ($foreignKeys as $fk) {
                    if (in_array('user_id', $fk->getLocalColumns())) {
                        $table->dropForeign($fk->getName());
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            // Restaurar la FK solo si existe la columna
            if (Schema::hasColumn('codes', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users');
            }
        });
    }
};
