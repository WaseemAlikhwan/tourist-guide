<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('auth_provider')->nullable()->after('password');
            $table->string('auth_provider_id')->nullable()->after('auth_provider');
            $table->string('avatar')->nullable()->after('auth_provider_id');
            $table->index(['auth_provider', 'auth_provider_id'], 'users_auth_provider_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_auth_provider_index');
            $table->dropColumn(['auth_provider', 'auth_provider_id', 'avatar']);
        });
    }
};
