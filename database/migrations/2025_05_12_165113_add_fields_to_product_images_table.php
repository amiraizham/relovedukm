<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_images', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->foreignId('product_id')
                  ->after('id')
                  ->nullable()
                  ->constrained('products')
                  ->onDelete('cascade');
    
            $table->string('image_path')->after('product_id');
        });
    }
    public function down(): void
    {
        Schema::table('product_images', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'image_path']);
        });
    }
    
};
