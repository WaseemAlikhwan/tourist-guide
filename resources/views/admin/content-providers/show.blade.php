@extends('admin.layouts.app')

@section('title', 'تفاصيل طلب مزوّد محتوى سياحي')

@section('content')
    <div class="breadcrumb-custom">
        <a href="{{ route('admin.dashboard') }}">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('admin.content-providers.index') }}">طلبات مزوّدي المحتوى</a>
        <span>/</span>
        <span>طلب رقم {{ $application->id }}</span>
    </div>

    <div class="page-header">
        <div>
            <h1>
                <i class="bi bi-person-badge me-2"></i>
                طلب مزوّد محتوى رقم #{{ $application->id }}
            </h1>
            <p class="subtitle">
                مراجعة بيانات النشاط والمستندات المرفقة ومن ثم قبول أو رفض الطلب.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-custom p-4 mb-3">
                <h5 class="mb-3">بيانات صاحب/ممثل النشاط</h5>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>الاسم الكامل:</strong>
                        <div>{{ $application->full_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>البريد الإلكتروني:</strong>
                        <div>{{ $application->email }}</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>نوع النشاط:</strong>
                        @php
                            $labels = [
                                'hotel' => 'فندق',
                                'travel_company' => 'شركة سياحية',
                                'event_organizer' => 'منظم فعاليات',
                                'tour_guide' => 'دليل سياحي',
                            ];
                        @endphp
                        <div>
                            <span class="badge bg-light text-dark">
                                {{ $labels[$application->activity_type] ?? $application->activity_type }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>تاريخ تقديم الطلب:</strong>
                        <div>{{ $application->created_at?->format('Y-m-d H:i') }}</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>الحالة الحالية للطلب:</strong>
                        <div class="mt-1">
                            @if($application->status === 'pending')
                                <span class="badge bg-warning text-dark">قيد المراجعة</span>
                            @elseif($application->status === 'approved')
                                <span class="badge bg-success">معتمد</span>
                            @else
                                <span class="badge bg-danger">مرفوض</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>مستخدم النظام المرتبط:</strong>
                        <div>
                            @if($application->user)
                                {{ $application->user->name }} (ID: {{ $application->user->id }})
                            @else
                                <span class="text-muted">غير متوفر</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-custom p-4">
                <h5 class="mb-3">المستندات المرفقة</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong>عقد تسجيل تجاري:</strong>
                        <div class="mt-1 mb-1">
                            @if($application->commercial_registration_path)
                                <span class="badge bg-success">مرفق</span>
                            @else
                                <span class="badge bg-secondary">غير مرفق</span>
                            @endif
                        </div>
                        <div class="mt-1">
                            @if($application->commercial_registration_path)
                                <a href="{{ route('admin.content-providers.documents.view', [$application, 'document' => 'commercial-registration']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-text"></i> عرض المستند
                                </a>
                            @else
                                <span class="text-muted">غير مرفق</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <strong>ترخيص سياحي:</strong>
                        <div class="mt-1 mb-1">
                            @if($application->tourism_license_path)
                                <span class="badge bg-success">مرفق</span>
                            @else
                                <span class="badge bg-secondary">غير مرفق</span>
                            @endif
                        </div>
                        <div class="mt-1">
                            @if($application->tourism_license_path)
                                <a href="{{ route('admin.content-providers.documents.view', [$application, 'document' => 'tourism-license']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-text"></i> عرض المستند
                                </a>
                            @else
                                <span class="text-muted">غير مرفق</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <strong>وثيقة ملكية / إدارة النشاط:</strong>
                        <div class="mt-1 mb-1">
                            @if($application->ownership_document_path)
                                <span class="badge bg-success">مرفق</span>
                            @else
                                <span class="badge bg-secondary">غير مرفق</span>
                            @endif
                        </div>
                        <div class="mt-1">
                            @if($application->ownership_document_path)
                                <a href="{{ route('admin.content-providers.documents.view', [$application, 'document' => 'ownership-document']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-text"></i> عرض المستند
                                </a>
                            @else
                                <span class="text-muted">غير مرفق</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom p-4 mb-3">
                <h5 class="mb-3">إجراءات الإدارة</h5>

                <form action="{{ route('admin.content-providers.approve', $application) }}" method="POST" class="mb-3" onsubmit="return confirm('تأكيد اعتماد الطلب؟')">
                    @csrf
                    <div class="mb-2">
                        <label for="admin_notes_approve" class="form-label">ملاحظات (اختياري عند القبول)</label>
                        <select class="form-select form-select-sm mb-2" onchange="applyTemplate('approve', this.value)">
                            <option value="">قالب سريع (اختياري)</option>
                            <option value="تمت مراجعة المستندات واعتماد الطلب بنجاح.">اعتماد قياسي</option>
                            <option value="تم اعتماد الطلب، يرجى استكمال بيانات الملف الشخصي وإضافة أول نشاط.">اعتماد مع توجيه للبداية</option>
                        </select>
                        <textarea
                            id="admin_notes_approve"
                            name="admin_notes"
                            class="form-control"
                            rows="2"
                            placeholder="يمكنك كتابة ملاحظة داخلية حول سبب أو شروط الاعتماد (لن تُعرض للمستخدم في هذه النسخة)."
                        >{{ old('admin_notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" {{ $application->status === 'approved' ? 'disabled' : '' }}>
                        <i class="bi bi-check-circle me-1"></i>
                        اعتماد مزوّد المحتوى وتفعيل تسجيل الدخول
                    </button>
                </form>

                <form action="{{ route('admin.content-providers.reject', $application) }}" method="POST" onsubmit="return confirm('تأكيد رفض الطلب؟')">
                    @csrf
                    <div class="mb-2">
                        <label for="admin_notes_reject" class="form-label">سبب الرفض/إلغاء التفعيل (إلزامي)</label>
                        <select class="form-select form-select-sm mb-2" onchange="applyTemplate('reject', this.value)">
                            <option value="">قالب سريع (اختياري)</option>
                            <option value="المستندات المرفقة غير كافية أو غير واضحة. يرجى إعادة الرفع بجودة أفضل.">مشكلة في المستندات</option>
                            <option value="بيانات النشاط غير مكتملة. يرجى استكمال البيانات وإعادة التقديم.">بيانات غير مكتملة</option>
                            <option value="نوع النشاط الحالي لا يطابق شروط اعتماد مزوّد المحتوى في المنصة.">نشاط غير مطابق للشروط</option>
                            <option value="تم إلغاء تفعيل المزوّد بعد المراجعة الإدارية بسبب مخالفة سياسات المنصة.">إلغاء تفعيل مزوّد معتمد</option>
                        </select>
                        <textarea
                            id="admin_notes_reject"
                            name="admin_notes"
                            class="form-control"
                            rows="2"
                            placeholder="مثلاً: مستندات غير واضحة، نشاط غير سياحي، بيانات غير مكتملة، أو سبب إلغاء التفعيل..."
                            required
                        ></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-1"></i>
                        رفض الطلب / إلغاء تفعيل المزوّد
                    </button>
                </form>
            </div>

            <div class="card-custom p-4">
                <h5 class="mb-3">سجل المراجعة</h5>
                <div class="mb-3">
                    <h6 class="mb-2">الخط الزمني للطلب</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            تم تقديم الطلب: {{ $application->created_at?->format('Y-m-d H:i') }}
                        </li>
                        <li class="mb-2">
                            @if($application->status === 'pending')
                                <i class="bi bi-hourglass-split text-warning me-2"></i>
                                الطلب قيد المراجعة
                            @elseif($application->status === 'approved')
                                <i class="bi bi-patch-check-fill text-success me-2"></i>
                                تم الاعتماد
                            @else
                                <i class="bi bi-x-octagon-fill text-danger me-2"></i>
                                تم الرفض
                            @endif
                        </li>
                        @if($application->reviewed_at)
                            <li>
                                <i class="bi bi-calendar-event text-secondary me-2"></i>
                                تاريخ المراجعة: {{ $application->reviewed_at->format('Y-m-d H:i') }}
                            </li>
                        @endif
                    </ul>
                </div>
                @if($application->reviewed_at)
                    <p class="mb-2">
                        <strong>تاريخ آخر مراجعة:</strong><br>
                        {{ $application->reviewed_at->format('Y-m-d H:i') }}
                    </p>
                    <p class="mb-2">
                        <strong>المدير الذي قام بالمراجعة:</strong><br>
                        {{ optional($application->admin)->name ?? 'غير معروف' }}
                    </p>
                    <p class="mb-0">
                        <strong>ملاحظات الإدارة:</strong><br>
                        @if($application->admin_notes)
                            {{ $application->admin_notes }}
                        @else
                            <span class="text-muted">لا توجد ملاحظات مسجلة.</span>
                        @endif
                    </p>
                @else
                    <p class="text-muted mb-0">
                        لم يتم بعد مراجعة هذا الطلب من قبل الإدارة.
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function applyTemplate(type, value) {
        if (!value) return;
        const target = type === 'approve'
            ? document.getElementById('admin_notes_approve')
            : document.getElementById('admin_notes_reject');
        if (target) target.value = value;
    }
</script>
@endpush

