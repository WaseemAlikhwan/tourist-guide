@extends('provider.layouts.app')

@section('title', 'إشعارات المزوّد')

@section('content')
<div class="provider-page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>إشعارات المزوّد</h1>
        <p>تابع أحدث الحجوزات والإشعارات المتعلقة بمحتواك.</p>
    </div>
    @if($user->unreadNotifications->count() > 0)
        <form method="POST" action="{{ route('provider.notifications.read-all') }}">
            @csrf
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-check-double me-1"></i>
                تحديد الكل كمقروء
            </button>
        </form>
    @endif
</div>

<div class="provider-panel p-3">
    @if($notifications->isEmpty())
        <div class="text-center p-4">
            <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
            <p class="text-muted mb-0">لا توجد إشعارات حالياً.</p>
        </div>
    @else
        @foreach($notifications as $notification)
            <div class="border rounded-3 p-3 mb-2 {{ $notification->read_at ? 'bg-white' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        @if(!$notification->read_at)
                            <span class="provider-badge provider-badge-success mb-2">جديد</span>
                        @endif
                        <div class="fw-semibold mb-1">{{ $notification->data['message'] ?? 'إشعار جديد' }}</div>
                        <div class="text-muted small">
                            {{ $notification->created_at->diffForHumans() }}
                            @if(!empty($notification->data['booking_reference']))
                                - رقم الحجز: {{ $notification->data['booking_reference'] }}
                            @endif
                        </div>
                    </div>
                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('provider.notifications.read', $notification->id) }}">
                            @csrf
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
