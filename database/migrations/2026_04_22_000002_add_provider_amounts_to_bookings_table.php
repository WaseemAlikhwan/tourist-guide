<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('provider_earned_amount', 12, 2)
                ->nullable()
                ->after('total_price');
            $table->decimal('platform_fee_amount', 12, 2)
                ->nullable()
                ->after('provider_earned_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['provider_earned_amount', 'platform_fee_amount']);
        });
    }
};
