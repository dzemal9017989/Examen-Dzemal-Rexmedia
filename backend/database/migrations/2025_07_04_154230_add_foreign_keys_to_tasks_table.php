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
        // This methods says that we are going to change the existing tasks table
        Schema::table('tasks', function (Blueprint $table) {
            // This defines that the status_id column will have the correct data type
            $table->unsignedBigInteger('status_id')->change();

            // This adds a foreign key constraint to the status_id column that points to the id column in the statuses table
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // This deletes the foreign key constraint from the status_id column
            $table->dropForeign(['status_id']);
        });
    }
};

