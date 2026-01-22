<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpUserAgentToCodesTable extends Migration
{
    public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
        });
    }

    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            $table->dropColumn(['ip', 'user_agent']);
        });
    }
}
