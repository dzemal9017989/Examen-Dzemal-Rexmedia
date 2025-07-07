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
        // This changes the name of the table from Dutch to English
        Schema::rename('statussen', 'statuses');

        // This method renames the columns in the statuses table to be more in English
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
