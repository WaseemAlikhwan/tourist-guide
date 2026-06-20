<?php

namespace Database\Seeders;
use App\Models\User;
use App\Services\ActivityAssociationService;
use Database\Seeders\AdminSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders الأساسية
        $this->call(AdminSeeder::class);
        $this->call(BadgeSeeder::class);
        $this->call(CouponSeeder::class);

        // Seeders البيانات الرئيسية
        $this->call(DestinationSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(UserSeeder::class);

        // Seeders البيانات المرتبطة
        $this->call(TravelBasicSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(GallerySeeder::class);
        $this->call(BookingSeeder::class);

        // بناء تحليل ارتباطات الأنشطة/الفعاليات بناءً على الحجوزات المدفوعة
        $rows = app(ActivityAssociationService::class)->rebuild();
        $this->command?->info("تم توليد {$rows} سجل ارتباط للفعاليات والأنشطة.");
    }
}
