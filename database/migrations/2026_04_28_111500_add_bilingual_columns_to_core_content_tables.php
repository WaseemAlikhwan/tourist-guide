<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_ar');
            $table->string('country_ar')->nullable()->after('country');
            $table->string('country_en')->nullable()->after('country_ar');
            $table->text('description_ar')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_ar');
            $table->json('custom_sections_ar')->nullable()->after('custom_sections');
            $table->json('custom_sections_en')->nullable()->after('custom_sections_ar');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_ar');
            $table->string('type_ar')->nullable()->after('type');
            $table->string('type_en')->nullable()->after('type_ar');
            $table->string('location_ar')->nullable()->after('location');
            $table->string('location_en')->nullable()->after('location_ar');
            $table->text('description_ar')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_ar');
            $table->text('highlights_ar')->nullable()->after('highlights');
            $table->text('highlights_en')->nullable()->after('highlights_ar');
            $table->text('whats_included_ar')->nullable()->after('whats_included');
            $table->text('whats_included_en')->nullable()->after('whats_included_ar');
            $table->text('whats_not_included_ar')->nullable()->after('whats_not_included');
            $table->text('whats_not_included_en')->nullable()->after('whats_not_included_ar');
            $table->text('additional_info_ar')->nullable()->after('additional_info');
            $table->text('additional_info_en')->nullable()->after('additional_info_ar');
            $table->text('payment_policy_ar')->nullable()->after('payment_policy');
            $table->text('payment_policy_en')->nullable()->after('payment_policy_ar');
            $table->text('cancellation_policy_ar')->nullable()->after('cancellation_policy');
            $table->text('cancellation_policy_en')->nullable()->after('cancellation_policy_ar');
            $table->json('custom_sections_ar')->nullable()->after('custom_sections');
            $table->json('custom_sections_en')->nullable()->after('custom_sections_ar');
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_ar');
            $table->text('description_ar')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_ar');
            $table->string('address_ar')->nullable()->after('address');
            $table->string('address_en')->nullable()->after('address_ar');
            $table->json('amenities_ar')->nullable()->after('amenities');
            $table->json('amenities_en')->nullable()->after('amenities_ar');
        });

        Schema::table('travel_basics', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('content_ar')->nullable()->after('content');
            $table->text('content_en')->nullable()->after('content_ar');
            $table->json('items_ar')->nullable()->after('items');
            $table->json('items_en')->nullable()->after('items_ar');
            $table->json('custom_sections_ar')->nullable()->after('custom_sections');
            $table->json('custom_sections_en')->nullable()->after('custom_sections_ar');
        });

        DB::table('destinations')->update([
            'name_ar' => DB::raw('name'),
            'country_ar' => DB::raw('country'),
            'description_ar' => DB::raw('description'),
            'custom_sections_ar' => DB::raw('custom_sections'),
        ]);

        DB::table('activities')->update([
            'name_ar' => DB::raw('name'),
            'type_ar' => DB::raw('type'),
            'location_ar' => DB::raw('location'),
            'description_ar' => DB::raw('description'),
            'highlights_ar' => DB::raw('highlights'),
            'whats_included_ar' => DB::raw('whats_included'),
            'whats_not_included_ar' => DB::raw('whats_not_included'),
            'additional_info_ar' => DB::raw('additional_info'),
            'payment_policy_ar' => DB::raw('payment_policy'),
            'cancellation_policy_ar' => DB::raw('cancellation_policy'),
            'custom_sections_ar' => DB::raw('custom_sections'),
        ]);

        DB::table('hotels')->update([
            'name_ar' => DB::raw('name'),
            'description_ar' => DB::raw('description'),
            'address_ar' => DB::raw('address'),
            'amenities_ar' => DB::raw('amenities'),
        ]);

        DB::table('travel_basics')->update([
            'title_ar' => DB::raw('title'),
            'content_ar' => DB::raw('content'),
            'items_ar' => DB::raw('items'),
            'custom_sections_ar' => DB::raw('custom_sections'),
        ]);
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'name_ar',
                'name_en',
                'country_ar',
                'country_en',
                'description_ar',
                'description_en',
                'custom_sections_ar',
                'custom_sections_en',
            ]);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'name_ar',
                'name_en',
                'type_ar',
                'type_en',
                'location_ar',
                'location_en',
                'description_ar',
                'description_en',
                'highlights_ar',
                'highlights_en',
                'whats_included_ar',
                'whats_included_en',
                'whats_not_included_ar',
                'whats_not_included_en',
                'additional_info_ar',
                'additional_info_en',
                'payment_policy_ar',
                'payment_policy_en',
                'cancellation_policy_ar',
                'cancellation_policy_en',
                'custom_sections_ar',
                'custom_sections_en',
            ]);
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'name_ar',
                'name_en',
                'description_ar',
                'description_en',
                'address_ar',
                'address_en',
                'amenities_ar',
                'amenities_en',
            ]);
        });

        Schema::table('travel_basics', function (Blueprint $table) {
            $table->dropColumn([
                'title_ar',
                'title_en',
                'content_ar',
                'content_en',
                'items_ar',
                'items_en',
                'custom_sections_ar',
                'custom_sections_en',
            ]);
        });
    }
};
