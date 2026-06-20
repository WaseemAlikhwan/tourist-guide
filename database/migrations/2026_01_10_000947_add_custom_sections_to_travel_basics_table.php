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
        Schema::table('travel_basics', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_basics', 'custom_sections')) {
                $table->json('custom_sections')->nullable()->after('items');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_basics', function (Blueprint $table) {
            if (Schema::hasColumn('travel_basics', 'custom_sections')) {
                $table->dropColumn('custom_sections');
            }
        });
    }
};
