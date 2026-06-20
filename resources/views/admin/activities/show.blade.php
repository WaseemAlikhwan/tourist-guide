@extends('admin.layouts.app')

@section('title', 'تفاصيل النشاط | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.activities.index') }}" class="text-decoration-none">الأنشطة</a></span>
        <span>›</span>
        <span>تفاصيل النشاط</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تفاصيل النشاط: {{ $activity->name }} 🎯</h1>
            <p class="subtitle">نظرة شاملة على بيانات النشاط</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> تعديل
            </a>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card card-custom h-100">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="fw-bold">المعلومات الأساسية</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>الاسم:</strong> {{ $activity->name }}</li>
                            <li class="mb-2"><strong>الوجهة:</strong> {{ $activity->destination?->name ?? '-' }}</li>
                            <li class="mb-2"><strong>مزوّد المحتوى:</strong>
                                @if($activity->provider)
                                    {{ $activity->provider->name }} ({{ $activity->provider->email }})
                                @else
                                    —
                                @endif
                            </li>
                            <li class="mb-2"><strong>النوع:</strong> {{ $activity->type ?? '-' }}</li>
                            <li class="mb-2"><strong>السعر:</strong>
                                @if(!is_null($activity->price))
                                    {{ number_format($activity->price, 0) }} ل.س
                                @else
                                    -
                                @endif
                            </li>
                            <li class="mb-2"><strong>التقييم:</strong> {{ $activity->rating ?? 0 }} / 5</li>
                            <li class="mb-2"><strong>الموقع:</strong> {{ $activity->location ?? '-' }}</li>
                            <li class="mb-2"><strong>تاريخ الإضافة:</strong> {{ $activity->created_at?->format('Y-m-d') }}</li>
                        </ul>
                    </div>

                    <div>
                        <h5 class="fw-bold">الوصف</h5>
                        <p class="text-muted mb-0">
                            {{ $activity->description ? $activity->description : 'لا يوجد وصف للنشاط.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-custom h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">صورة النشاط</h5>
                    @if($activity->image)
                        <img src="{{ asset('storage/' . $activity->image) }}"
                             alt="{{ $activity->name }}"
                             style="width: 100%; border-radius: 12px; max-height: 380px; object-fit: cover; border: 2px solid #ddd;">
                    @else
                        <div class="text-center text-muted">
                            لا توجد صورة مرفقة لهذا النشاط.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">الحجوزات</h5>
                        <span class="badge bg-info">{{ $activity->bookings->count() }} حجز</span>
                    </div>

                    @if($activity->bookings->isEmpty())
                        <div class="text-muted text-center py-4">لا توجد حجوزات بعد.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>رقم الحجز</th>
                                    <th>تاريخ الحجز</th>
                                    <th>عدد الأشخاص</th>
                                    <th>السعر الإجمالي</th>
                                    <th>الحالة</th>
                                    <th style="width: 180px;">إجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activity->bookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->user?->name ?? 'مستخدم محذوف' }}</div>
                                            <small class="text-muted">{{ $booking->user?->email ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $booking->booking_reference }}</span>
                                        </td>
                                        <td>{{ $booking->booking_date->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary">{{ $booking->number_of_people }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ number_format($booking->total_price, 0) }}</span>
                                            <small class="text-muted">ل.س</small>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'cancelled' => 'danger',
                                                    'completed' => 'info'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'معلق',
                                                    'confirmed' => 'مؤكد',
                                                    'cancelled' => 'ملغي',
                                                    'completed' => 'مكتمل'
                                                ];
                                                $color = $statusColors[$booking->status] ?? 'secondary';
                                                $label = $statusLabels[$booking->status] ?? $booking->status;
                                            @endphp
                                            <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">{{ $label }}</span>
                                        </td>
                                        <td>
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="btn btn-sm btn-success" title="تأكيد الحجز">
                                                        <i class="bi bi-check-circle"></i> تأكيد
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- التقييمات -->
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">التقييمات (تقييم واحد فقط لكل مستخدم)</h5>
                        <span class="badge bg-warning">{{ $activity->reviews->count() }} تقييم</span>
                    </div>

                    @if($activity->reviews->isEmpty())
                        <div class="text-muted text-center py-4">لا توجد تقييمات بعد.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th style="width: 180px;">التقييم</th>
                                    <th>تاريخ التقييم</th>
                                    <th style="width: 150px;">إجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activity->reviews as $review)
                                    <tr id="review-row-{{ $review->id }}" class="{{ $review->is_approved ? 'table-success' : '' }}">
                                        <td>{{ $review->user?->name ?? 'مستخدم' }}</td>
                                        <td>
                                            <div id="review-form-{{ $review->id }}">
                                                @if(!$review->is_approved)
                                                    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="review-form d-flex align-items-center gap-2" data-review-id="{{ $review->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="number" name="rating" min="1" max="5" class="form-control form-control-sm w-50" value="{{ $review->rating }}" required>
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">حفظ</button>
                                                    </form>
                                                @else
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success">{{ $review->rating }} ⭐</span>
                                                        <span class="text-muted small">(معتمد)</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $review->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if(!$review->is_approved)
                                                    <form action="{{ route('admin.reviews.toggle-approval', $review) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="اعتماد">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.reviews.toggle-approval', $review) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" title="إلغاء الاعتماد">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('حذف هذا التقييم؟')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- التعليقات -->
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">التعليقات (يمكن للمستخدم التعليق عدة مرات)</h5>
                        <span class="badge bg-primary">{{ $activity->comments->count() }} تعليق</span>
                    </div>

                    @if($activity->comments->isEmpty())
                        <div class="text-muted text-center py-4">لا توجد تعليقات بعد.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>التعليق</th>
                                    <th style="width: 120px;">الحالة</th>
                                    <th>التاريخ</th>
                                    <th style="width: 250px;">إجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activity->comments as $comment)
                                    <tr id="comment-row-{{ $comment->id }}" class="{{ $comment->is_approved ? 'table-success' : '' }}">
                                        <td>{{ $comment->user?->name ?? 'مستخدم' }}</td>
                                        <td>
                                            <div id="comment-form-{{ $comment->id }}">
                                                @if(!$comment->is_approved)
                                                    <form action="{{ route('admin.comments.update', $comment) }}" method="POST" class="comment-form d-flex align-items-center gap-2" data-comment-id="{{ $comment->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="تعليق">{{ $comment->comment }}</textarea>
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">حفظ</button>
                                                    </form>
                                                @else
                                                    <div class="text-muted">{{ $comment->comment }}</div>
                                                    <span class="badge bg-success small">معتمد</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($comment->is_approved)
                                                <span class="badge bg-success">معتمد</span>
                                            @else
                                                <span class="badge bg-warning">غير معتمد</span>
                                            @endif
                                        </td>
                                        <td>{{ $comment->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if(!$comment->is_approved)
                                                    <form action="{{ route('admin.comments.toggle-approval', $comment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="اعتماد">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.comments.toggle-approval', $comment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" title="إلغاء الاعتماد">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('حذف هذا التعليق؟')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('head')
    <script>
        // معالجة حفظ التقييمات
        document.querySelectorAll('.review-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const reviewId = this.dataset.reviewId;
                const submitBtn = this.querySelector('button[type="submit"]');
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'جاري الحفظ...';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                    }
                })
                .then(response => response.json().catch(() => ({ success: true })))
                .then(data => {
                    if (data.success !== false) {
                        // إخفاء النموذج وإظهار التقييم المعتمد
                        const formContainer = document.getElementById('review-form-' + reviewId);
                        const rating = formData.get('rating');
                        formContainer.innerHTML = `
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success">${rating} ⭐</span>
                                <span class="text-muted small">(معتمد)</span>
                            </div>
                        `;
                        
                        // تحديث صف الجدول
                        const row = document.getElementById('review-row-' + reviewId);
                        row.classList.add('table-success');
                        
                        // تحديث أزرار الإجراءات
                        const actionsCell = row.querySelector('td:last-child');
                        actionsCell.innerHTML = `
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.reviews.toggle-approval', ':id') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="إلغاء الاعتماد">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.destroy', ':id') }}" method="POST" onsubmit="return confirm('حذف هذا التقييم؟')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </div>
                        `.replace(/:id/g, reviewId);

                        // إظهار رسالة النجاح
                        showSuccessMessage('تم حفظ واعتماد التقييم بنجاح');
                        
                        // إعادة تحميل الصفحة بعد ثانيتين
                        setTimeout(() => window.location.reload(), 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'حفظ';
                    alert('حدث خطأ أثناء حفظ التقييم');
                });
            });
        });

        // معالجة حفظ التعليقات
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const commentId = this.dataset.commentId;
                const submitBtn = this.querySelector('button[type="submit"]');
                const commentText = formData.get('comment');
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'جاري الحفظ...';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                    }
                })
                .then(response => response.json().catch(() => ({ success: true })))
                .then(data => {
                    if (data.success !== false) {
                        // إخفاء النموذج وإظهار التعليق المعتمد
                        const formContainer = document.getElementById('comment-form-' + commentId);
                        formContainer.innerHTML = `
                            <div class="text-muted">${commentText}</div>
                            <span class="badge bg-success small">معتمد</span>
                        `;
                        
                        // تحديث صف الجدول
                        const row = document.getElementById('comment-row-' + commentId);
                        row.classList.add('table-success');
                        
                        // تحديث الحالة
                        const statusCell = row.querySelector('td:nth-child(3)');
                        statusCell.innerHTML = '<span class="badge bg-success">معتمد</span>';
                        
                        // تحديث أزرار الإجراءات
                        const actionsCell = row.querySelector('td:last-child');
                        actionsCell.innerHTML = `
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.comments.toggle-approval', ':id') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="إلغاء الاعتماد">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.comments.destroy', ':id') }}" method="POST" onsubmit="return confirm('حذف هذا التعليق؟')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </div>
                        `.replace(/:id/g, commentId);

                        // إظهار رسالة النجاح
                        showSuccessMessage('تم حفظ واعتماد التعليق بنجاح');
                        
                        // إعادة تحميل الصفحة بعد ثانيتين
                        setTimeout(() => window.location.reload(), 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'حفظ';
                    alert('حدث خطأ أثناء حفظ التعليق');
                });
            });
        });

        function showSuccessMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.content-shell').insertBefore(alertDiv, document.querySelector('.content-shell').firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
    @endpush
@endsection

