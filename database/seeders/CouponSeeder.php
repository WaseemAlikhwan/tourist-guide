<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME2024',
                'description' => 'كوبون ترحيبي للعملاء الجدد - خصم 20%',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'minimum_purchase' => 100,
                'usage_limit' => 100,
                'usage_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER50',
                'description' => 'كوبون صيفي - خصم 50 وحدة على الحجوزات',
                'discount_type' => 'fixed',
                'discount_value' => 50,
                'minimum_purchase' => 200,
                'usage_limit' => 50,
                'usage_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'VIP15',
                'description' => 'خصم VIP - 15% على جميع الحجوزات',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'minimum_purchase' => 150,
                'usage_limit' => null,
                'usage_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'NEWUSER100',
                'description' => 'هدية للعملاء الجدد - خصم 100 وحدة',
                'discount_type' => 'fixed',
                'discount_value' => 100,
                'minimum_purchase' => 500,
                'usage_limit' => 200,
                'usage_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'EARLYBIRD',
                'description' => 'عرض الحجز المبكر - خصم 25%',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'minimum_purchase' => 300,
                'usage_limit' => 30,
                'usage_count' => 0,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonth(),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}




