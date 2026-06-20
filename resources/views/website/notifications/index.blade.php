@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'Notifications' : 'الإشعارات')

@push('head')
<style>
    .notification-item {
        border-right: 4px solid;
        transition: all 0.3s ease;
    }
    
    .notification-item.unread {
        border-right-color: var(--primary);
        background: linear-gradient(90deg, rgba(14, 165, 233, 0.05) 0%, transparent 100%);
    }
    
    .notification-item.read {
        border-right-color: var(--border);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2">
                        <i class="fas fa-bell text-primary me-2"></i>
                        {{ $isEn ? 'Notifications' : 'الإشعارات' }}
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-pill ms-2">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </h1>
                    <p class="text-muted mb-0">{{ $isEn ? 'Stay updated with the latest activity' : 'تابع آخر التحديثات والأنشطة' }}</p>
                </div>
                
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-double me-2"></i>
                            {{ $isEn ? 'Mark All as Read' : 'تحديد الكل كمقروء' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

            @if($notifications->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>{{ $isEn ? 'No notifications' : 'لا توجد إشعارات' }}</h4>
                    <p>{{ $isEn ? 'Your notifications will appear here' : 'ستظهر هنا جميع إشعاراتك' }}</p>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item notification-item {{ $notification->read_at ? 'read' : 'unread' }} border-0 py-4">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    @if(!$notification->read_at)
                                        <span class="badge bg-primary rounded-pill mb-2">{{ $isEn ? 'New' : 'جديد' }}</span>
                                    @endif
                                    
                                    <h5 class="mb-3 fw-bold">
                                        @if($notification->type == 'App\Notifications\BookingConfirmed')
                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                            {{ $isEn ? 'Booking Confirmed' : 'تأكيد حجز' }}
                                        @elseif($notification->type == 'App\Notifications\BadgeEarned')
                                            <i class="fas fa-medal text-warning me-2"></i>
                                            {{ $isEn ? 'New Badge' : 'شارة جديدة' }}
                                        @elseif(($notification->data['type'] ?? null) === 'content_provider_application_reviewed')
                                            <i class="fas fa-user-check {{ ($notification->data['status'] ?? null) === 'approved' ? 'text-success' : 'text-danger' }} me-2"></i>
                                            {{ $notification->data['title'] ?? ($isEn ? 'Content Provider Application Review' : 'مراجعة طلب مزوّد المحتوى') }}
                                        @else
                                            <i class="fas fa-bell me-2"></i>
                                            {{ $isEn ? 'Notification' : 'إشعار' }}
                                        @endif
                                    </h5>
                                    
                                    <p class="mb-3 fs-5">{{ $notification->data['message'] }}</p>

                                    @if(($notification->data['type'] ?? null) === 'content_provider_application_reviewed')
                                        <div class="mb-2">
                                            <span class="badge {{ ($notification->data['status'] ?? null) === 'approved' ? 'bg-success' : 'bg-danger' }} p-2">
                                                {{ $isEn ? 'Result:' : 'النتيجة:' }} {{ $notification->data['result'] ?? (($notification->data['status'] ?? null) === 'approved' ? ($isEn ? 'Approved' : 'قبول') : ($isEn ? 'Rejected' : 'رفض')) }}
                                            </span>
                                        </div>

                                        @if(!empty($notification->data['admin_notes']))
                                            <div class="alert alert-light border mb-2">
                                                <strong>{{ $isEn ? 'Admin Notes:' : 'ملاحظات الإدارة:' }}</strong>
                                                <div class="mt-1">{{ $notification->data['admin_notes'] }}</div>
                                            </div>
                                        @endif

                                        @if(!empty($notification->data['next_steps']) && is_array($notification->data['next_steps']))
                                            <div class="mb-2">
                                                <strong>{{ $isEn ? 'Next Steps:' : 'الخطوات التالية:' }}</strong>
                                                <ol class="mb-0 mt-1 ps-3">
                                                    @foreach($notification->data['next_steps'] as $step)
                                                        <li>{{ $step }}</li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                        @endif

                                        @if(!empty($notification->data['action_url']))
                                            <div class="mt-2">
                                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-arrow-left me-1"></i>
                                                    {{ $notification->data['action_label'] ?? ($isEn ? 'Continue' : 'متابعة') }}
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    @if(isset($notification->data['booking_reference']))
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark p-2">
                                                <i class="fas fa-hashtag me-1"></i>
                                                {{ $isEn ? 'Booking Ref:' : 'رقم الحجز:' }} <strong>{{ $notification->data['booking_reference'] }}</strong>
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if(isset($notification->data['badge_name']))
                                        <div class="mb-2">
                                            <span class="badge bg-warning text-dark p-2 fs-6">
                                                {{ $notification->data['badge_icon'] }} 
                                                {{ $notification->data['badge_name'] }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if(isset($notification->data['points']))
                                        <div class="mb-2">
                                            <span class="badge bg-success p-2 fs-6">
                                                <i class="fas fa-plus me-1"></i>
                                                {{ $notification->data['points'] }} {{ $isEn ? 'points' : 'نقطة' }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" 
                                              method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    title="{{ $isEn ? 'Mark as read' : 'تحديد كمقروء' }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" 
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="{{ $isEn ? 'Delete' : 'حذف' }}"
                                                onclick="return confirm('{{ $isEn ? 'Are you sure you want to delete this notification?' : 'هل أنت متأكد من حذف هذا الإشعار؟' }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



