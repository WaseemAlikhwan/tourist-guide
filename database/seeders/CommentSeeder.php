<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $activities = Activity::all();

        if ($users->isEmpty() || $activities->isEmpty()) {
            $this->command->warn('لا توجد مستخدمين أو أنشطة! يرجى تشغيل UserSeeder و ActivitySeeder أولاً.');
            return;
        }

        $comments = [
            'تجربة رائعة! الجولة كانت ممتازة والدليل محترف جداً. أنصح الجميع بزيارة دمشق القديمة.',
            'جولة جميلة ومفيدة، لكن كانت طويلة قليلاً. بشكل عام تجربة جيدة.',
            'أفضل جولة في حياتي! المسجد الأموي رائع والبلدة القديمة ساحرة.',
            'قلعة حلب تاريخية ومثيرة للإعجاب. يستحق الزيارة بالتأكيد.',
            'تدمر مكان سحري! الآثار رائعة والدليل كان خبيراً في التاريخ.',
            'المسرح الروماني في بصرى مذهل! تجربة لا تُنسى.',
            'معلولا قرية جميلة جداً، واللغة الآرامية شيء فريد.',
            'الشاطئ نظيف والجو جميل. يوم ممتع على البحر.',
            'السوق المسقوف في حلب رائع! يمكنك قضاء ساعات في التجول.',
            'جولة ممتعة ومفيدة. أنصح بها للعائلات.',
            'المكان يستحق الزيارة مرة أخرى. تجربة لا تُنسى.',
            'الدليل كان متعاوناً جداً وشرح كل شيء بالتفصيل.',
            'الأسعار معقولة والخدمة ممتازة. سأعود قريباً.',
            'تجربة رائعة مع العائلة. الأطفال استمتعوا كثيراً.',
            'المكان نظيف ومنظم. الخدمة احترافية.',
        ];

        $users = $users->values();
        $userCount = $users->count();

        foreach ($activities as $activityIndex => $activity) {
            $numComments = min(3, $userCount);

            for ($i = 0; $i < $numComments; $i++) {
                $user = $users[($activityIndex + $i) % $userCount];
                $comment = $comments[($activityIndex + $i) % count($comments)];
                $isApproved = (($activityIndex + $i) % 4) !== 0;

                Comment::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'activity_id' => $activity->id,
                        'comment' => $comment,
                    ],
                    [
                        'is_approved' => $isApproved,
                    ]
                );
            }
        }

        $this->command->info('تم إنشاء ' . Comment::count() . ' تعليق بنجاح.');
    }
}
