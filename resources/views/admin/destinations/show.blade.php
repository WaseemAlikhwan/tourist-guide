@extends('admin.layouts.app')

@section('title', 'تفاصيل الوجهة | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.destinations.index') }}" class="text-decoration-none">الوجهات</a></span>
        <span>›</span>
        <span>تفاصيل الوجهة</span>
    </div>

    <div class="page-header">
        <div>
            <h1>{{ $destination->name }} 🧭</h1>
            <p class="subtitle">تفاصيل الوجهة السياحية</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> تعديل
            </a>
            <a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">معلومات الوجهة</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">اسم الوجهة</label>
                        <p class="fs-5 mb-0">{{ $destination->name }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">الدولة</label>
                        <p class="fs-5 mb-0">
                            <span class="badge bg-primary-subtle text-primary fs-6">{{ $destination->country }}</span>
                        </p>
                    </div>

                    @if($destination->description)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">الوصف</label>
                            <p class="mb-0" style="line-height: 1.8;">{{ $destination->description }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">عدد الأنشطة</label>
                        <p class="fs-5 mb-0">
                            <span class="badge bg-success-subtle text-success fs-6">{{ $destination->activities->count() }}</span>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">تاريخ الإضافة</label>
                        <p class="mb-0">{{ $destination->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold text-muted small">آخر تحديث</label>
                        <p class="mb-0">{{ $destination->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($destination->activities->count() > 0)
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="card-title mb-4">الأنشطة المرتبطة</h5>
                        <div class="list-group list-group-flush">
                            @foreach($destination->activities as $activity)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $activity->type }}</div>
                                        <small class="text-muted">السعر: {{ $activity->price }} - التقييم: {{ $activity->rating }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark">{{ $activity->location }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-custom">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-activity" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-3">لا توجد أنشطة مرتبطة بهذه الوجهة</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title mb-4">صورة الوجهة</h5>
                    @if($destination->image)
                        <img src="{{ asset('storage/' . $destination->image) }}" 
                             alt="{{ $destination->name }}" 
                             class="img-fluid rounded"
                             style="width: 100%; border-radius: 12px;">
                    @else
                        <div class="text-center py-5" style="background: #f0f0f0; border-radius: 12px;">
                            <i class="bi bi-image" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">لا توجد صورة</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-custom mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">إجراءات سريعة</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> تعديل الوجهة
                        </a>
                        <form action="{{ route('admin.destinations.destroy', $destination) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الوجهة؟ سيتم حذف جميع الأنشطة المرتبطة بها أيضاً.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> حذف الوجهة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

