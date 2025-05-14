<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_approved')->default(0); // 0 = pending, 1 = approved
        });
    }
    

    /**
     * Reverse the migrations.
     */
    /**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('is_approved');
    });
}

};
