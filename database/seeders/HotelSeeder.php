<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Destination;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على الوجهات من قاعدة البيانات
        $destinations = Destination::all();
        
        if ($destinations->isEmpty()) {
            $this->command->warn('لا توجد وجهات في قاعدة البيانات. يرجى تشغيل DestinationSeeder أولاً.');
            return;
        }

        // فنادق لكل وجهة
        $hotels = [
            // فنادق دمشق
            [
                'destination_name' => 'دمشق',
                'name' => 'فندق أمية دمشق',
                'description' => 'فندق 5 نجوم فاخر في قلب العاصمة، يوفر إطلالة رائعة على المدينة التاريخية وخدمات مميزة.',
                'address' => 'شارع البارون، دمشق، سوريا',
                'phone' => '+963 11 231 2000',
                'email' => 'info@omayyad-damascus.com',
                'website' => 'https://www.omayyad-damascus.com',
                'star_rating' => 5,
                'price_per_night' => 120000,
                'latitude' => 33.5150,
                'longitude' => 36.2980,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مسبح', 'صالة ألعاب رياضية', 'مطعم', 'بار', 'خدمة الاستقبال 24/7', 'مصعد', 'مكيف هواء', 'تلفزيون'],
                'is_active' => true,
            ],
            [
                'destination_name' => 'دمشق',
                'name' => 'فندق الشام',
                'description' => 'فندق 4 نجوم أنيق يقع في المنطقة التجارية من دمشق، قريب من الأسواق التاريخية والمعالم السياحية.',
                'address' => 'شارع فؤاد الأول، دمشق القديمة، سوريا',
                'phone' => '+963 11 332 1000',
                'email' => 'reservations@sham-hotel.sy',
                'website' => 'https://www.sham-hotel.sy',
                'star_rating' => 4,
                'price_per_night' => 80000,
                'latitude' => 33.5090,
                'longitude' => 36.2920,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'غرفة إفطار', 'خدمة الغرف', 'مكيف هواء', 'تلفزيون', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],
            [
                'destination_name' => 'دمشق',
                'name' => 'فندق فينيسيا',
                'description' => 'فندق 3 نجوم مريح يقدم إقامة مريحة بأسعار معقولة في موقع استراتيجي.',
                'address' => 'شارع 29 أيار، دمشق، سوريا',
                'phone' => '+963 11 333 2200',
                'email' => 'info@venice-hotel.sy',
                'star_rating' => 3,
                'price_per_night' => 50000,
                'latitude' => 33.5120,
                'longitude' => 36.2900,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'مكيف هواء', 'تلفزيون', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],

            // فنادق حلب
            [
                'destination_name' => 'حلب',
                'name' => 'فندق شيراتون حلب',
                'description' => 'فندق 5 نجوم فاخر في مركز المدينة، يوفر خدمة عالية المستوى وإطلالات رائعة على قلعة حلب التاريخية.',
                'address' => 'شارع الجمهورية، حلب، سوريا',
                'phone' => '+963 21 212 2000',
                'email' => 'info@aleppo-sheraton.com',
                'website' => 'https://www.aleppo-sheraton.com',
                'star_rating' => 5,
                'price_per_night' => 110000,
                'latitude' => 36.2050,
                'longitude' => 37.1400,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مسبح', 'صالة ألعاب رياضية', 'مطعم', 'بار', 'سبا', 'خدمة الاستقبال 24/7', 'مصعد', 'مكيف هواء', 'خدمة الغسيل'],
                'is_active' => true,
            ],
            [
                'destination_name' => 'حلب',
                'name' => 'فندق حلب الكبير',
                'description' => 'فندق 4 نجوم يقع بالقرب من السوق المسقوف التاريخي، يوفر إقامة مريحة في موقع مثالي للسياح.',
                'address' => 'شارع باب الفرج، حلب القديمة، سوريا',
                'phone' => '+963 21 212 3000',
                'email' => 'bookings@aleppo-grand.com',
                'star_rating' => 4,
                'price_per_night' => 75000,
                'latitude' => 36.2000,
                'longitude' => 37.1350,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'غرفة إفطار', 'خدمة الغرف', 'مكيف هواء', 'تلفزيون', 'ميني بار'],
                'is_active' => true,
            ],

            // فنادق تدمر
            [
                'destination_name' => 'تدمر',
                'name' => 'فندق تدمر السياحي',
                'description' => 'فندق 3 نجوم قريب من الموقع الأثري، يوفر إقامة مريحة للزوار المهتمين بالتاريخ والآثار.',
                'address' => 'طريق تدمر الأثري، تدمر، سوريا',
                'phone' => '+963 31 912 2000',
                'email' => 'info@palmyra-tourist.com',
                'star_rating' => 3,
                'price_per_night' => 45000,
                'latitude' => 34.5600,
                'longitude' => 38.2800,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'مكيف هواء', 'تلفزيون', 'خدمة النقل من/إلى المطار'],
                'is_active' => true,
            ],
            [
                'destination_name' => 'تدمر',
                'name' => 'منتجع تدمر',
                'description' => 'منتجع 4 نجوم يوفر إقامة هادئة ومريحة مع إطلالة على الصحراء والآثار التاريخية.',
                'address' => 'طريق تدمر - دير الزور، تدمر، سوريا',
                'phone' => '+963 31 912 3000',
                'email' => 'reservations@palmyra-resort.com',
                'star_rating' => 4,
                'price_per_night' => 70000,
                'latitude' => 34.5550,
                'longitude' => 38.2750,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مسبح', 'مطعم', 'بار', 'مكيف هواء', 'تلفزيون', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],

            // فنادق بصرى
            [
                'destination_name' => 'بصرى',
                'name' => 'فندق بصرى الأثري',
                'description' => 'فندق 3 نجوم قريب من المسرح الروماني التاريخي، يوفر إقامة مريحة للسياح المهتمين بالآثار الرومانية.',
                'address' => 'شارع المسرح الروماني، بصرى، سوريا',
                'phone' => '+963 15 812 1000',
                'email' => 'info@bosra-archaeological.com',
                'star_rating' => 3,
                'price_per_night' => 40000,
                'latitude' => 32.5200,
                'longitude' => 36.4850,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'مكيف هواء', 'تلفزيون', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],

            // فنادق اللاذقية
            [
                'destination_name' => 'اللاذقية',
                'name' => 'فندق الشاطئ الأزرق',
                'description' => 'فندق 5 نجوم على شاطئ البحر الأبيض المتوسط، يوفر إطلالات خلابة وإقامة فاخرة.',
                'address' => 'الكورنيش البحري، اللاذقية، سوريا',
                'phone' => '+963 41 240 0000',
                'email' => 'info@bluebeach-lattakia.com',
                'website' => 'https://www.bluebeach-lattakia.com',
                'star_rating' => 5,
                'price_per_night' => 130000,
                'latitude' => 35.5200,
                'longitude' => 35.7850,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مسبح', 'صالة ألعاب رياضية', 'مطعم', 'بار', 'شاطئ خاص', 'خدمة الغرف', 'سبا', 'مكيف هواء'],
                'is_active' => true,
            ],
            [
                'destination_name' => 'اللاذقية',
                'name' => 'فندق راديسون اللاذقية',
                'description' => 'فندق 4 نجوم في قلب المدينة الساحلية، قريب من الشاطئ والمناطق التجارية.',
                'address' => 'شارع الرئيس حافظ الأسد، اللاذقية، سوريا',
                'phone' => '+963 41 241 1000',
                'email' => 'reservations@radisson-lattakia.com',
                'star_rating' => 4,
                'price_per_night' => 85000,
                'latitude' => 35.5150,
                'longitude' => 35.7800,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مسبح', 'مطعم', 'بار', 'غرفة إفطار', 'خدمة الغرف', 'مكيف هواء', 'تلفزيون'],
                'is_active' => true,
            ],
            [
                'destination_name' => 'اللاذقية',
                'name' => 'فندق الكورنيش',
                'description' => 'فندق 3 نجوم بأسعار معقولة مع إطلالة على البحر، مناسب للعائلات والسياح.',
                'address' => 'الكورنيش، اللاذقية، سوريا',
                'phone' => '+963 41 242 2000',
                'email' => 'info@corniche-hotel.sy',
                'star_rating' => 3,
                'price_per_night' => 55000,
                'latitude' => 35.5100,
                'longitude' => 35.7750,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'مكيف هواء', 'تلفزيون', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],

            // فنادق طرطوس
            [
                'destination_name' => 'طرطوس',
                'name' => 'فندق الأرواد',
                'description' => 'فندق 4 نجوم على الشاطئ مع إطلالة على جزيرة أرواد، يوفر إقامة مريحة وإطلالات بحرية رائعة.',
                'address' => 'الكورنيش البحري، طرطوس، سوريا',
                'phone' => '+963 43 221 0000',
                'email' => 'info@arwad-tartous.com',
                'star_rating' => 4,
                'price_per_night' => 80000,
                'latitude' => 34.8900,
                'longitude' => 35.8900,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مسبح', 'مطعم', 'شاطئ خاص', 'خدمة الغرف', 'مكيف هواء', 'تلفزيون'],
                'is_active' => true,
            ],
            [
                'destination_name' => 'طرطوس',
                'name' => 'فندق طرطوس السياحي',
                'description' => 'فندق 3 نجوم قريب من القلعة الصليبية والمناطق السياحية، يوفر إقامة مريحة بأسعار معقولة.',
                'address' => 'شارع الكورنيش، طرطوس، سوريا',
                'phone' => '+963 43 222 1000',
                'email' => 'bookings@tartous-tourist.com',
                'star_rating' => 3,
                'price_per_night' => 50000,
                'latitude' => 34.8850,
                'longitude' => 35.8850,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'مكيف هواء', 'تلفزيون', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],

            // فنادق معلولا
            [
                'destination_name' => 'معلولا',
                'name' => 'بيت الضيافة معلولا',
                'description' => 'بيت ضيافة تقليدي في القرية الجبلية، يوفر تجربة فريدة في قرية معلولا التاريخية مع إطلالات جبلية رائعة.',
                'address' => 'معلولا، ريف دمشق، سوريا',
                'phone' => '+963 11 234 5000',
                'email' => 'info@maaloula-guesthouse.com',
                'star_rating' => 3,
                'price_per_night' => 35000,
                'latitude' => 33.8450,
                'longitude' => 36.5450,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'غرفة إفطار', 'مكيف هواء', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],

            // فنادق صيدنايا
            [
                'destination_name' => 'صيدنايا',
                'name' => 'فندق صيدنايا الجبلي',
                'description' => 'فندق 3 نجوم في المنطقة الجبلية، قريب من دير السيدة العذراء، يوفر إقامة هادئة ومريحة.',
                'address' => 'صيدنايا، ريف دمشق، سوريا',
                'phone' => '+963 11 234 6000',
                'email' => 'info@saidnaya-mountain.com',
                'star_rating' => 3,
                'price_per_night' => 40000,
                'latitude' => 33.7000,
                'longitude' => 36.3850,
                'amenities' => ['واي فاي مجاني', 'موقف سيارات', 'مطعم', 'مكيف هواء', 'تلفزيون', 'خدمة الاستقبال 24/7'],
                'is_active' => true,
            ],
        ];

        foreach ($hotels as $hotelData) {
            // البحث عن الوجهة بالاسم
            $destination = $destinations->firstWhere('name_ar', $hotelData['destination_name']);
            
            if (!$destination) {
                $this->command->warn("لم يتم العثور على الوجهة: {$hotelData['destination_name']}");
                continue;
            }

            // استخراج اسم الوجهة من البيانات
            $destinationName = $hotelData['destination_name'];
            unset($hotelData['destination_name']);

            // إضافة معرف الوجهة
            $hotelData['destination_id'] = $destination->id;

            // إنشاء الفندق بالحقول الثنائية
            Hotel::updateOrCreate(
                [
                    'destination_id' => $hotelData['destination_id'],
                    'name_ar' => $hotelData['name'],
                ],
                [
                'destination_id' => $hotelData['destination_id'],
                'name_ar' => $hotelData['name'],
                'name_en' => $hotelData['name'],
                'description_ar' => $hotelData['description'] ?? null,
                'description_en' => $hotelData['description'] ?? null,
                'address_ar' => $hotelData['address'] ?? null,
                'address_en' => $hotelData['address'] ?? null,
                'phone' => $hotelData['phone'] ?? null,
                'email' => $hotelData['email'] ?? null,
                'website' => $hotelData['website'] ?? null,
                'star_rating' => $hotelData['star_rating'] ?? null,
                'price_per_night' => $hotelData['price_per_night'] ?? null,
                'latitude' => $hotelData['latitude'] ?? null,
                'longitude' => $hotelData['longitude'] ?? null,
                'amenities_ar' => $hotelData['amenities'] ?? null,
                'amenities_en' => $hotelData['amenities'] ?? null,
                'is_active' => $hotelData['is_active'] ?? true,
                ]
            );
            
            $this->command->info("تم إنشاء فندق: {$hotelData['name']} في {$destinationName}");
        }

        $this->command->info('تم إنشاء جميع الفنادق بنجاح!');
    }
}
