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
            $table->text('highlights')->nullable()->after('duration_label');
            $table->text('whats_included')->nullable()->after('highlights');
            $table->text('whats_not_included')->nullable()->after('whats_included');
            $table->text('additional_info')->nullable()->after('whats_not_included');
            $table->text('payment_policy')->nullable()->after('additional_info');
            $table->text('cancellation_policy')->nullable()->after('payment_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'highlights',
                'whats_included',
                'whats_not_included',
                'additional_info',
                'payment_policy',
                'cancellation_policy'
            ]);
        });
    }
};
