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
        Schema::rename('taaks', 'tasks');
        // This renames the columns in the tasks table to be more in English
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('gebruiker_id', 'user_id');
            $table->renameColumn('titel', 'title');
            $table->renameColumn('beschrijving', 'description');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taaks', function (Blueprint $table) {
            //
        });
    }
};
