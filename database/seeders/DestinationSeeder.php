<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nameTranslations = [
            'دمشق' => 'Damascus',
            'حلب' => 'Aleppo',
            'تدمر' => 'Palmyra',
            'بصرى' => 'Bosra',
            'اللاذقية' => 'Latakia',
            'طرطوس' => 'Tartus',
            'معلولا' => 'Maaloula',
            'صيدنايا' => 'Saidnaya',
        ];

        $countryTranslations = [
            'سوريا' => 'Syria',
        ];

        $descriptionTranslations = [
            'عاصمة سوريا وأقدم عاصمة مأهولة في العالم، تتميز بتاريخها العريق وتراثها الثقافي الغني.' => 'The capital of Syria and one of the oldest continuously inhabited capitals in the world, known for its deep history and rich cultural heritage.',
            'ثاني أكبر مدينة في سوريا، تشتهر بقلعتها التاريخية وأسواقها التقليدية القديمة.' => 'The second largest city in Syria, famous for its historic citadel and traditional old markets.',
            'مدينة أثرية تاريخية تعود إلى العصور القديمة، إحدى أهم المواقع الأثرية في العالم.' => 'An ancient archaeological city dating back to classical times, considered one of the world\'s most important heritage sites.',
            'مدينة أثرية رومانية قديمة تشتهر بمسرحها الروماني الكبير الذي يعتبر من أجمل المسارح في العالم.' => 'An ancient Roman city renowned for its grand Roman theater, regarded as one of the most beautiful in the world.',
            'مدينة ساحلية على البحر الأبيض المتوسط، تشتهر بشواطئها الجميلة ومناخها المعتدل.' => 'A Mediterranean coastal city known for its beautiful beaches and mild climate.',
            'مدينة ساحلية تاريخية على ساحل البحر الأبيض المتوسط، تتميز بقلعتها الصليبية القديمة.' => 'A historic coastal city on the Mediterranean, distinguished by its old Crusader castle.',
            'قرية جبلية تاريخية تشتهر بديرها القديم واللغة الآرامية التي لا تزال تُستخدم فيها.' => 'A historic mountain village famous for its ancient monastery and the continued use of the Aramaic language.',
            'بلدة جبلية تاريخية تشتهر بديرها الأرثوذكسي القديم الذي يعود إلى القرن الخامس الميلادي.' => 'A historic mountain town known for its ancient Orthodox monastery that dates back to the 5th century AD.',
        ];

        $destinations = [
            [
                'name' => 'دمشق',
                'country' => 'سوريا',
                'description' => 'عاصمة سوريا وأقدم عاصمة مأهولة في العالم، تتميز بتاريخها العريق وتراثها الثقافي الغني.',
                'latitude' => 33.5138,
                'longitude' => 36.2765,
                'image' => 'destinations/damascus.jpg',
            ],
            [
                'name' => 'حلب',
                'country' => 'سوريا',
                'description' => 'ثاني أكبر مدينة في سوريا، تشتهر بقلعتها التاريخية وأسواقها التقليدية القديمة.',
                'latitude' => 36.2021,
                'longitude' => 37.1343,
                'image' => 'destinations/aleppo.jpg',
            ],
            [
                'name' => 'تدمر',
                'country' => 'سوريا',
                'description' => 'مدينة أثرية تاريخية تعود إلى العصور القديمة، إحدى أهم المواقع الأثرية في العالم.',
                'latitude' => 34.5581,
                'longitude' => 38.2739,
                'image' => 'destinations/palmyra.jpg',
            ],
            [
                'name' => 'بصرى',
                'country' => 'سوريا',
                'description' => 'مدينة أثرية رومانية قديمة تشتهر بمسرحها الروماني الكبير الذي يعتبر من أجمل المسارح في العالم.',
                'latitude' => 32.5186,
                'longitude' => 36.4819,
                'image' => 'destinations/bosra.jpg',
            ],
            [
                'name' => 'اللاذقية',
                'country' => 'سوريا',
                'description' => 'مدينة ساحلية على البحر الأبيض المتوسط، تشتهر بشواطئها الجميلة ومناخها المعتدل.',
                'latitude' => 35.5167,
                'longitude' => 35.7833,
                'image' => 'destinations/lattakia.jpg',
            ],
            [
                'name' => 'طرطوس',
                'country' => 'سوريا',
                'description' => 'مدينة ساحلية تاريخية على ساحل البحر الأبيض المتوسط، تتميز بقلعتها الصليبية القديمة.',
                'latitude' => 34.8886,
                'longitude' => 35.8864,
                'image' => 'destinations/tartous.jpg',
            ],
            [
                'name' => 'معلولا',
                'country' => 'سوريا',
                'description' => 'قرية جبلية تاريخية تشتهر بديرها القديم واللغة الآرامية التي لا تزال تُستخدم فيها.',
                'latitude' => 33.8444,
                'longitude' => 36.5458,
                'image' => 'destinations/maaloula.jpg',
            ],
            [
                'name' => 'صيدنايا',
                'country' => 'سوريا',
                'description' => 'بلدة جبلية تاريخية تشتهر بديرها الأرثوذكسي القديم الذي يعود إلى القرن الخامس الميلادي.',
                'latitude' => 33.7000,
                'longitude' => 36.3833,
                'image' => 'destinations/saidnaya.jpg',
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::updateOrCreate(
                ['name_ar' => $destination['name']],
                [
                'name_ar' => $destination['name'],
                'name_en' => $nameTranslations[$destination['name']] ?? $destination['name'],
                'country_ar' => $destination['country'],
                'country_en' => $countryTranslations[$destination['country']] ?? $destination['country'],
                'description_ar' => $destination['description'] ?? null,
                'description_en' => isset($destination['description']) ? ($descriptionTranslations[$destination['description']] ?? $destination['description']) : null,
                'latitude' => $destination['latitude'] ?? null,
                'longitude' => $destination['longitude'] ?? null,
                'image' => $destination['image'] ?? null,
                'custom_sections_ar' => null,
                'custom_sections_en' => null,
                ]
            );
        }
    }
}
