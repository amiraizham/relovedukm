<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('user1_matricnum');
            $table->string('user2_matricnum');
            $table->timestamps();

            // Optional index for performance
            $table->index(['user1_matricnum', 'user2_matricnum']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
