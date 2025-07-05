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
        $table->id(); // Default primary key column
        $table->foreignId('gebruiker_id')->constrained('users')->onDelete('cascade');
        $table->string('titel');
        $table->text('beschrijving')->nullable(); // 
        $table->foreignId('status_id')->constrained('statussen'); // column for the status of the task, referencing the 'statussen' table
        $table->date('deadline'); // date column for the deadline of the task
        $table->timestamps(); // Deafult timestamps for created_at and updated_at
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
