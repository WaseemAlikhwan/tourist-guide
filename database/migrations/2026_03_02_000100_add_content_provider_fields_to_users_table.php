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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_content_provider')
                ->default(false)
                ->after('role');

            $table->enum('content_provider_status', ['pending', 'approved', 'rejected'])
                ->nullable()
                ->after('is_content_provider');

            $table->string('activity_type')
                ->nullable()
                ->after('content_provider_status');

            $table->boolean('can_login')
                ->default(true)
                ->after('activity_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_content_provider',
                'content_provider_status',
                'activity_type',
                'can_login',
            ]);
        });
    }
};

