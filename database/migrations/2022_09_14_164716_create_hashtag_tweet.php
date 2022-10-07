<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('hashtag_tweet', function (Blueprint $table) {
            $table->unsignedBigInteger('idHashtag');
            $table->unsignedBigInteger('idTweet');
            $table->primary(['idHashtag', 'idTweet']);
            $table->foreign('idHashtag')
                ->references('id')
                ->on('hashtags')
                ->onDelete('cascade');
            $table->foreign('idTweet')
                ->references('id')
                ->on('tweets')
                ->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('hashtag_tweet');
    }
};
