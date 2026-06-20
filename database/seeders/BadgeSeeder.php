<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'مستكشف مبتدئ',
                'slug' => 'explorer-beginner',
                'description' => 'قم بأول حجز لك',
                'icon' => '🗺️',
                'color' => '#6366f1',
                'points_required' => 0,
            ],
            [
                'name' => 'محب السفر',
                'slug' => 'travel-lover',
                'description' => 'احجز 5 أنشطة مختلفة',
                'icon' => '✈️',
                'color' => '#8b5cf6',
                'points_required' => 500,
            ],
            [
                'name' => 'مخطط رحلات',
                'slug' => 'trip-planner',
                'description' => 'أنشئ أول جدول رحلة',
                'icon' => '📋',
                'color' => '#06b6d4',
                'points_required' => 50,
            ],
            [
                'name' => 'ناقد محترف',
                'slug' => 'professional-reviewer',
                'description' => 'اكتب 10 تقييمات',
                'icon' => '⭐',
                'color' => '#f59e0b',
                'points_required' => 300,
            ],
            [
                'name' => 'جامع الوجهات',
                'slug' => 'destination-collector',
                'description' => 'أضف 20 وجهة للمفضلة',
                'icon' => '🏆',
                'color' => '#10b981',
                'points_required' => 200,
            ],
            [
                'name' => 'مستكشف العالم',
                'slug' => 'world-explorer',
                'description' => 'زر 10 دول مختلفة',
                'icon' => '🌍',
                'color' => '#3b82f6',
                'points_required' => 2000,
            ],
            [
                'name' => 'عضو فضي',
                'slug' => 'silver-member',
                'description' => 'اصل لمستوى العضوية الفضية',
                'icon' => '🥈',
                'color' => '#C0C0C0',
                'points_required' => 2000,
            ],
            [
                'name' => 'عضو ذهبي',
                'slug' => 'gold-member',
                'description' => 'اصل لمستوى العضوية الذهبية',
                'icon' => '🥇',
                'color' => '#FFD700',
                'points_required' => 5000,
            ],
            [
                'name' => 'عضو بلاتيني',
                'slug' => 'platinum-member',
                'description' => 'اصل لمستوى العضوية البلاتينية',
                'icon' => '💎',
                'color' => '#E5E4E2',
                'points_required' => 10000,
            ],
            [
                'name' => 'صياد الصفقات',
                'slug' => 'deal-hunter',
                'description' => 'استخدم 5 كوبونات',
                'icon' => '🎟️',
                'color' => '#ec4899',
                'points_required' => 400,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}




