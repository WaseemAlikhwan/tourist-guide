<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->enum('provider_review_status', ['pending_review', 'approved', 'rejected'])
                ->default('approved')
                ->after('provider_id');
            $table->timestamp('provider_reviewed_at')->nullable()->after('provider_review_status');
            $table->index('provider_review_status');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['provider_review_status']);
            $table->dropColumn(['provider_review_status', 'provider_reviewed_at']);
        });
    }
};
