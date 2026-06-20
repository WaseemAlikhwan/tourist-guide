@extends('admin.layouts.app')

@section('title', 'تفاصيل الحجز | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.bookings.index') }}" class="text-decoration-none">الحجوزات</a></span>
        <span>›</span>
        <span>تفاصيل الحجز</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تفاصيل الحجز: {{ $booking->booking_reference }} 🎫</h1>
            <p class="subtitle">معلومات كاملة عن الحجز</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- معلومات الحجز -->
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">معلومات الحجز</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">رقم الحجز</label>
                                <div class="fw-bold fs-5">{{ $booking->booking_reference }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">تاريخ الحجز</label>
                                <div class="fw-bold">{{ $booking->booking_date->format('Y-m-d') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">الحالة</label>
                                <div>
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
                                    <span class="badge bg-{{ $color }}-subtle text-{{ $color }} fs-6">{{ $label }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">حالة الدفع</label>
                                <div>
                                    @php
                                        $paymentColors = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $paymentLabels = [
                                            'pending' => 'لم يدفع',
                                            'paid' => 'تم الدفع',
                                            'cancelled' => 'ملغي'
                                        ];
                                        $paymentColor = $paymentColors[$booking->payment_status ?? 'pending'] ?? 'secondary';
                                        $paymentLabel = $paymentLabels[$booking->payment_status ?? 'pending'] ?? 'غير محدد';
                                    @endphp
                                    <span class="badge bg-{{ $paymentColor }}-subtle text-{{ $paymentColor }} fs-6">{{ $paymentLabel }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">عدد الأشخاص</label>
                                <div class="fw-bold">{{ $booking->number_of_people }} شخص</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">السعر الإجمالي</label>
                                <div class="fw-bold text-success fs-5">{{ number_format($booking->total_price, 0) }} ل.س</div>
                            </div>
                        </div>
                    </div>

                    @if($booking->payment_method || $booking->payment_note)
                        <div class="row mb-3">
                            @if($booking->payment_method)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">طريقة الدفع</label>
                                        <div>
                                            <span class="badge bg-info-subtle text-info fs-6">
                                                <i class="bi bi-credit-card"></i> {{ $booking->payment_method }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($booking->payment_note)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">ملاحظة الدفع</label>
                                        <div class="p-2 bg-light rounded">{{ $booking->payment_note }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($booking->special_requests)
                        <div class="mb-3">
                            <label class="text-muted small">طلبات خاصة</label>
                            <div class="p-3 bg-light rounded">{{ $booking->special_requests }}</div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="text-muted small">تاريخ الإنشاء</label>
                        <div>{{ $booking->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات المستخدم -->
        <div class="col-md-4">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">معلومات المستخدم</h5>
                    <div class="mb-2">
                        <label class="text-muted small">الاسم</label>
                        <div class="fw-bold">{{ $booking->user?->name ?? 'مستخدم محذوف' }}</div>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small">البريد الإلكتروني</label>
                        <div>{{ $booking->user?->email ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- معلومات النشاط -->
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">معلومات النشاط</h5>
                    <div class="mb-2">
                        <label class="text-muted small">اسم النشاط</label>
                        <div class="fw-bold">{{ $booking->activity?->name ?? 'نشاط محذوف' }}</div>
                    </div>
                    @if($booking->activity?->destination)
                        <div class="mb-2">
                            <label class="text-muted small">الوجهة</label>
                            <div>{{ $booking->activity->destination->name }}</div>
                        </div>
                    @endif
                    @if($booking->activity?->price)
                        <div class="mb-2">
                            <label class="text-muted small">سعر النشاط</label>
                            <div class="fw-bold">{{ number_format($booking->activity->price, 0) }} ل.س</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

     <!-- أزرار الإجراءات -->
     <div class="card card-custom">
         <div class="card-body">
             <h5 class="fw-bold mb-3">إجراءات الحجز</h5>
             
             @if($booking->status !== 'completed' && $booking->status !== 'cancelled')
                 @if(($booking->payment_status ?? 'pending') === 'pending')
                     <!-- نموذج تأكيد الدفع -->
                     <div class="mb-4 p-4 bg-light rounded">
                         <h6 class="fw-bold mb-3">
                             <i class="bi bi-credit-card"></i> تأكيد الدفع
                         </h6>
                         <form action="{{ route('admin.bookings.confirm-payment', $booking) }}" method="POST" id="confirmPaymentForm">
                             @csrf
                             <div class="row g-3">
                                 <div class="col-md-6">
                                     <label class="form-label fw-semibold">
                                         طريقة الدفع <span class="text-danger">*</span>
                                     </label>
                                     <select name="payment_method" class="form-select" required id="payment_method_select">
                                         <option value="">اختر طريقة الدفع</option>
                                         <option value="شام كاش">شام كاش</option>
                                         <option value="فيزا">فيزا</option>
                                         <option value="ماستركارد">ماستركارد</option>
                                         <option value="نقداً">نقداً</option>
                                         <option value="تحويل بنكي">تحويل بنكي</option>
                                         <option value="أخرى">أخرى</option>
                                     </select>
                                     <small class="text-muted">يرجى اختيار طريقة الدفع المستخدمة</small>
                                 </div>
                                 <div class="col-md-6">
                                     <label class="form-label fw-semibold">ملاحظة (اختياري)</label>
                                     <input type="text" name="payment_note" class="form-control" 
                                            placeholder="مثال: رقم المعاملة، المرجع، إلخ..." 
                                            maxlength="500">
                                     <small class="text-muted">يمكنك إضافة أي ملاحظات إضافية عن الدفع</small>
                                 </div>
                                 <div class="col-12">
                                     <button type="submit" class="btn btn-success btn-lg">
                                         <i class="bi bi-check-circle"></i> تأكيد الدفع (تم دفع الفلوس)
                                     </button>
                                 </div>
                             </div>
                         </form>
                     </div>

                     <!-- إلغاء الحجز -->
                     <div class="mt-3">
                         <form action="{{ route('admin.bookings.cancel-unpaid', $booking) }}" method="POST" class="d-inline">
                             @csrf
                             <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('هل أنت متأكد من إلغاء الحجز؟ سيتم حذف الحجز من الجدول وإرسال إشعار للمستخدم بإلغاء الحجز بسبب التأخير في الدفع.')">
                                 <i class="bi bi-x-circle"></i> إلغاء الحجز (لم يدفع)
                             </button>
                         </form>
                     </div>
                 @endif
             @elseif($booking->status === 'completed')
                 <div class="alert alert-success mb-0">
                     <div class="d-flex align-items-center gap-2">
                         <i class="bi bi-check-circle fs-4"></i>
                         <div>
                             <strong>الحجز مكتمل - تم تأكيد الدفع</strong>
                             @if($booking->payment_method)
                                 <div class="mt-2">
                                     <span class="fw-semibold">طريقة الدفع:</span> 
                                     <span class="badge bg-info-subtle text-info">{{ $booking->payment_method }}</span>
                                 </div>
                             @endif
                             @if($booking->payment_note)
                                 <div class="mt-2">
                                     <span class="fw-semibold">ملاحظة:</span> {{ $booking->payment_note }}
                                 </div>
                             @endif
                         </div>
                     </div>
                 </div>
             @endif
         </div>
     </div>

     @push('head')
     <style>
         .form-select:focus, .form-control:focus {
             border-color: var(--primary);
             box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
         }
     </style>
     @endpush

     @push('scripts')
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const form = document.getElementById('confirmPaymentForm');
             if (form) {
                 form.addEventListener('submit', function(e) {
                     const paymentMethod = document.getElementById('payment_method_select').value;
                     if (!paymentMethod) {
                         e.preventDefault();
                         alert('يرجى اختيار طريقة الدفع');
                         document.getElementById('payment_method_select').focus();
                         return false;
                     }
                     
                     const paymentNote = form.querySelector('[name="payment_note"]').value;
                     let confirmMessage = 'هل أنت متأكد من تأكيد الدفع؟\n';
                     confirmMessage += 'طريقة الدفع: ' + paymentMethod;
                     if (paymentNote) {
                         confirmMessage += '\nملاحظة: ' + paymentNote;
                     }
                     confirmMessage += '\n\nسيصبح الحجز مكتملاً وسيتم إرسال إشعار للمستخدم.';
                     
                     if (!confirm(confirmMessage)) {
                         e.preventDefault();
                         return false;
                     }
                 });
             }
         });
     </script>
     @endpush
@endsection
