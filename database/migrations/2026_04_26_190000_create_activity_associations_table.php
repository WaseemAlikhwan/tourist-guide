<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->foreignId('associated_activity_id')->constrained('activities')->cascadeOnDelete();
            $table->unsignedInteger('co_occurrence_count')->default(0);
            $table->unsignedInteger('activity_occurrence_count')->default(0);
            $table->unsignedInteger('associated_occurrence_count')->default(0);
            $table->unsignedInteger('total_baskets')->default(0);
            $table->decimal('support', 8, 6)->default(0);
            $table->decimal('confidence', 8, 6)->default(0);
            $table->decimal('lift', 8, 6)->default(0);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->unique(['activity_id', 'associated_activity_id'], 'activity_associations_unique_pair');
            $table->index(['activity_id', 'confidence']);
            $table->index(['co_occurrence_count', 'lift']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_associations');
    }
};
