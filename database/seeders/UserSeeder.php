<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 2500,
                'tier' => 'silver',
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 1200,
                'tier' => 'bronze',
            ],
            [
                'name' => 'محمد خالد',
                'email' => 'mohammed@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 5500,
                'tier' => 'gold',
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'sara@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 300,
                'tier' => 'bronze',
            ],
            [
                'name' => 'خالد حسن',
                'email' => 'khalid@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 10500,
                'tier' => 'platinum',
            ],
            [
                'name' => 'ليلى محمود',
                'email' => 'layla@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 800,
                'tier' => 'bronze',
            ],
            [
                'name' => 'عمر يوسف',
                'email' => 'omar@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 4200,
                'tier' => 'silver',
            ],
            [
                'name' => 'نورا إبراهيم',
                'email' => 'nora@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'loyalty_points' => 150,
                'tier' => 'bronze',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
