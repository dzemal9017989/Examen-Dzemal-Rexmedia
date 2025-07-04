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
        Schema::rename('taaks', 'tasks');
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
