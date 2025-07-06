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
    // The create method creates a new table in the database called 'taaks'
    Schema::create('taaks', function (Blueprint $table) {
        $table->id(); // This makes a id column with a primary key
        $table->foreignId('gebruiker_id')->constrained('users')->onDelete('cascade'); // This points the id of the user who created the task, referencing the 'users' table
        $table->string('titel'); // This contains a string column for the title of the task
        $table->text('beschrijving')->nullable(); // This contains a text column for the description of the task, which can be empty (nullable)
        $table->foreignId('status_id')->constrained('statussen'); // column for the status of the task, referencing the 'statussen' table
        $table->date('deadline'); // date column for the deadline of the task
        $table->timestamps(); // Default timestamps for created_at and updated_at
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taaks');
    }
};
