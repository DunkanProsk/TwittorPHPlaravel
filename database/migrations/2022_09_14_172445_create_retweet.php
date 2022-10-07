<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retweet', function (Blueprint $table) {
            $table->unsignedBigInteger('idTweet');
            $table->unsignedBigInteger('idRetweet');
            $table->primary(['idTweet', 'idRetweet']);
            $table->foreign('idTweet')
                ->references('id')
                ->on('tweets')
                ->onDelete('cascade');
            $table->foreign('idRetweet')
                ->references('id')
                ->on('tweets')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('retweet');
    }
};
