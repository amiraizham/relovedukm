<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('receiver_matricnum')->after('sender_matricnum'); // Add after sender
        });
    }
    
    public function down()
    {
        Schema::table('messages', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('receiver_matricnum');
        });
    }
    
};
