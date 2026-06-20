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
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->enum('type', ['earned', 'redeemed', 'expired']);
            $table->string('reason');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add points column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('loyalty_points')->default(0)->after('email');
            $table->string('tier')->default('bronze')->after('loyalty_points'); // bronze, silver, gold, platinum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points', 'tier']);
        });
    }
};




