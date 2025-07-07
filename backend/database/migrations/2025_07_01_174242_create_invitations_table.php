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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id(); // This makes a id column with a primary key
            $table->string('email')->unique()->index(); // This column has a unique email address and is indexed for faster lookups
            $table->string('name'); // his column contains the name of the user being invited
            $table->string('role')->default('user'); // This column contains the role of the user being invited
            $table->string('token')->unique(); // This is a unique token for the invitation, ensuring that each invitation is distinct
            $table->timestamp('expires_at'); // This column contains data when the date and time expires
            $table->boolean('accepted')->default(false); // This coulmn shosw if the invitation has been accepted or not, defaulting to false
            $table->timestamps(); // This columns create the default timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
