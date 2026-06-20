<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn(['name', 'country', 'description', 'custom_sections']);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'type',
                'location',
                'description',
                'highlights',
                'whats_included',
                'whats_not_included',
                'additional_info',
                'payment_policy',
                'cancellation_policy',
                'custom_sections',
            ]);
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'address', 'amenities']);
        });

        Schema::table('travel_basics', function (Blueprint $table) {
            $table->dropColumn(['title', 'content', 'items', 'custom_sections']);
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->json('custom_sections')->nullable();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->text('highlights')->nullable();
            $table->text('whats_included')->nullable();
            $table->text('whats_not_included')->nullable();
            $table->text('additional_info')->nullable();
            $table->text('payment_policy')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->json('custom_sections')->nullable();
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->json('amenities')->nullable();
        });

        Schema::table('travel_basics', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->json('items')->nullable();
            $table->json('custom_sections')->nullable();
        });
    }
};
