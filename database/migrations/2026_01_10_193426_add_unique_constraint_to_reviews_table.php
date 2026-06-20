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
        // حذف التقييمات المكررة (الاحتفاظ بالأحدث فقط)
        $duplicates = \DB::table('reviews')
            ->select('user_id', 'activity_id', \DB::raw('MAX(id) as latest_id'))
            ->groupBy('user_id', 'activity_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            \DB::table('reviews')
                ->where('user_id', $duplicate->user_id)
                ->where('activity_id', $duplicate->activity_id)
                ->where('id', '!=', $duplicate->latest_id)
                ->delete();
        }

        // إضافة unique constraint لضمان تقييم واحد فقط لكل مستخدم لكل نشاط
        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unique(['user_id', 'activity_id'], 'reviews_user_activity_unique');
            });
        } catch (\Exception $e) {
            // في حالة وجود الـ constraint مسبقاً، نتجاهل الخطأ
            if (strpos($e->getMessage(), 'Duplicate key') === false && 
                strpos($e->getMessage(), 'already exists') === false) {
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'activity_id']);
        });
    }
};
