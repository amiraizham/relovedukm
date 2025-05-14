<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->string('sender_matricnum');
            $table->text('message');
            $table->timestamps();

            // Optional index for performance
            $table->index('sender_matricnum');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
