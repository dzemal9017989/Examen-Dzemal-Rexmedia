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
        // This updates the tasks table
        Schema::table('tasks', function (Blueprint $table) {
            // This makes the 'status_id' optional (nullable) and it changes the column 
            $table->unsignedBigInteger('status_id')->nullable()->change();
           
        });
    }

    public function down(): void
    {
        // This updates the table 'tasks' again
        Schema::table('tasks', function (Blueprint $table) {
            // This removes the external key binding on 'status_id'
            $table->dropForeign(['status_id']);
        });
    }
    //
};

