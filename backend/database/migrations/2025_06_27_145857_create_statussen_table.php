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
        Schema::create('statussen', function (Blueprint $table) {
            $table->id(); // This comtains an id
            $table->string('naam'); // The contains a string with column name called 'naam'
            $table->timestamps(); // This creates the default timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statussen');
    }
};
