<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Activity;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = Destination::all();
        $activities = Activity::all();

        if ($destinations->isEmpty() && $activities->isEmpty()) {
            $this->command->warn('لا توجد وجهات أو أنشطة! يرجى تشغيل DestinationSeeder و ActivitySeeder أولاً.');
            return;
        }

        // إضافة صور للوجهات
        foreach ($destinations as $destination) {
            $numImages = 3;
            for ($i = 0; $i < $numImages; $i++) {
                $imagePath = 'galleries/destination-' . $destination->id . '-' . ($i + 1) . '.jpg';
                Gallery::updateOrCreate(
                    [
                        'galleryable_type' => Destination::class,
                        'galleryable_id' => $destination->id,
                        'image_path' => $imagePath,
                    ],
                    [
                        'caption' => 'Photo of ' . $destination->name,
                        'order' => $i + 1,
                        'is_featured' => $i === 0,
                    ]
                );
            }
        }

        // إضافة صور للأنشطة
        foreach ($activities as $activity) {
            $numImages = 2;
            for ($i = 0; $i < $numImages; $i++) {
                $imagePath = 'galleries/activity-' . $activity->id . '-' . ($i + 1) . '.jpg';
                Gallery::updateOrCreate(
                    [
                        'galleryable_type' => Activity::class,
                        'galleryable_id' => $activity->id,
                        'image_path' => $imagePath,
                    ],
                    [
                        'caption' => 'Photo of ' . $activity->name,
                        'order' => $i + 1,
                        'is_featured' => $i === 0,
                    ]
                );
            }
        }
    }
}
