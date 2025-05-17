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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            
            $table->string('reviewer_matricnum');
            $table->string('seller_matricnum');
        
            $table->tinyInteger('rating')->unsigned(); // 1–5
            $table->text('comment');
            $table->timestamps();
        
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('reviewer_matricnum')->references('matricnum')->on('users')->onDelete('cascade');
            $table->foreign('seller_matricnum')->references('matricnum')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
