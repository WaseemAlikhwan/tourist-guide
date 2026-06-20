<?php

namespace App\Notifications;

use App\Models\ContentProviderApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentProviderApplicationReviewed extends Notification
{
    use Queueable;

    public function __construct(
        private readonly ContentProviderApplication $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isApproved = $this->application->status === 'approved';
        $actionUrl = $isApproved
            ? route('provider.dashboard')
            : route('provider.application-status');
        $nextSteps = $isApproved
            ? [
                'ادخل إلى لوحة مزوّد المحتوى.',
                'أكمل بيانات الحساب والنشاط.',
                'أضف أول نشاط/عرض وراجعه قبل النشر.',
            ]
            : [
                'راجع سبب الرفض في ملاحظات الإدارة.',
                'حدّث البيانات أو المستندات المطلوبة.',
                'أعد تقديم الطلب من صفحة مزوّد المحتوى.',
            ];

        return [
            'type' => 'content_provider_application_reviewed',
            'application_id' => $this->application->id,
            'status' => $this->application->status,
            'title' => $isApproved
                ? 'تم قبول طلب مزوّد المحتوى'
                : 'تم رفض طلب مزوّد المحتوى',
            'message' => $isApproved
                ? 'تمت الموافقة على طلبك كمزوّد محتوى. يمكنك الآن استخدام مزايا المزوّد.'
                : 'تم رفض طلبك كمزوّد محتوى. راجع ملاحظات الإدارة إن وُجدت وأعد التقديم.',
            'result' => $isApproved ? 'قبول' : 'رفض',
            'admin_notes' => $this->application->admin_notes,
            'next_steps' => $nextSteps,
            'action_label' => $isApproved ? 'الانتقال إلى لوحة المزوّد' : 'مراجعة حالة الطلب',
            'action_url' => $actionUrl,
            'reviewed_at' => optional($this->application->reviewed_at)?->toDateTimeString(),
        ];
    }
}

