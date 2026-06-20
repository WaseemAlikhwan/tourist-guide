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
        Schema::table('activities', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('image');
            $table->boolean('is_must_visit')->default(false)->after('is_featured');
            $table->date('event_date')->nullable()->after('is_must_visit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_must_visit', 'event_date']);
        });
    }
};
