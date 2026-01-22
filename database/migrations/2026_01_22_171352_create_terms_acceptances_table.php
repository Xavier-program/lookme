<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsAcceptancesTable extends Migration
{
    public function up()
    {
        Schema::create('terms_acceptances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role'); // user o girl

            $table->timestamp('accepted_at');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();

            $table->string('terms_version'); // ej. v1.0
            $table->string('accepted_from')->nullable(); // URL o pantalla

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('terms_acceptances');
    }
}
