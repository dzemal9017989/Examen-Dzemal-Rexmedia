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
        // Eerst de tabel hernoemen
        Schema::rename('statussen', 'statuses');

        // Daarna kolom hernoemen
        Schema::table('statuses', function (Blueprint $table) {
            $table->renameColumn('naam', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statussen', function (Blueprint $table) {
            //
        });
    }
};
