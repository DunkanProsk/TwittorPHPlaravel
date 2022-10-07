<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('userName');
            $table->string('userNickname');
            $table->string('passHash');
            $table->string('userBio');
            $table->string('linkPhoto');
        });

    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
