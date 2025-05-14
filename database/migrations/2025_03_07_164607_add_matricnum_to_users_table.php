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
        Schema::table('users', function (Blueprint $table) {
            $table->string('matricnum')->unique()->after('name'); // Add matricnum field
            $table->dropColumn('email'); // Remove email field (if not needed)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('matricnum');
            $table->string('email')->unique()->nullable(); // Restore email if needed
        });
    }
};
