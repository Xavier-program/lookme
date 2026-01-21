<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivateFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            // Solo los que faltan (privados)
            $table->string('photo_private_1')->nullable();
            $table->string('photo_private_2')->nullable();
            $table->string('photo_private_3')->nullable();
            $table->string('photo_private_4')->nullable();
            $table->string('photo_private_5')->nullable();
            $table->string('photo_private_6')->nullable();
            $table->string('video_private')->nullable();
            $table->text('description_private')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'photo_private_1','photo_private_2','photo_private_3',
                'photo_private_4','photo_private_5','photo_private_6',
                'video_private','description_private'
            ]);
        });
    }
}
