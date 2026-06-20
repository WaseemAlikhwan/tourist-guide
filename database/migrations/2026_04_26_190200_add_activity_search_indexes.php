<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->index('type');
            $table->index('destination_id');
            $table->index('event_date');
            $table->index('duration_minutes');
            $table->index('requires_booking');
            $table->index(['provider_review_status', 'rating']);
            $table->index(['provider_review_status', 'price']);
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['destination_id']);
            $table->dropIndex(['event_date']);
            $table->dropIndex(['duration_minutes']);
            $table->dropIndex(['requires_booking']);
            $table->dropIndex(['provider_review_status', 'rating']);
            $table->dropIndex(['provider_review_status', 'price']);
        });
    }
};
