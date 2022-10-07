<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('idCreator');
            $table->string('tweetText');
            $table->timestamp('tweetDateCreate');
            $table->foreign('idCreator')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tweets');
    }
};
