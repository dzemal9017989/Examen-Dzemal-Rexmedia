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
    // This makes a change in teh existing table and adds a new column called 'role'
    Schema::table('users', function (Blueprint $table) {
            // This makes a standard value of the column called user, which is the default role for users
            // This adds the column directly after the password column in the users table
            $table->string('role')->default('user')->after('password'); 

    });
}


    /**
     * Reverse the migrations.
     */
public function down(): void
{
    // This deletes the role column from the users table
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}

};
