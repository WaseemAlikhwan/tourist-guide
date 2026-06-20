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
        // نقل التعليقات الموجودة من reviews إلى comments (إن وجدت)
        if (Schema::hasColumn('reviews', 'comment')) {
            $reviewsWithComments = \DB::table('reviews')
                ->whereNotNull('comment')
                ->where('comment', '!=', '')
                ->get();

            foreach ($reviewsWithComments as $review) {
                \DB::table('comments')->insert([
                    'user_id' => $review->user_id,
                    'activity_id' => $review->activity_id,
                    'comment' => $review->comment,
                    'is_approved' => true,
                    'created_at' => $review->created_at ?? now(),
                    'updated_at' => $review->updated_at ?? now(),
                ]);
            }
        }

        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'comment')) {
                $table->dropColumn('comment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('comment')->nullable()->after('rating');
        });
    }
};
