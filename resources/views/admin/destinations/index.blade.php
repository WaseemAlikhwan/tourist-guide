@extends('admin.layouts.app')

@section('title', 'الوجهات | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span>الوجهات</span>
    </div>

    <div class="page-header">
        <div>
            <h1>الوجهات 🧭</h1>
            <p class="subtitle">إدارة جميع الوجهات السياحية في المنصة</p>
        </div>
        <a href="{{ route('admin.destinations.create') }}" class="primary-action d-flex align-items-center gap-3 text-decoration-none">
            <span class="icon">＋</span>
            <div class="text-end">
                <div class="fw-bold">إضافة وجهة</div>
                <small class="text-white-50">شارك مكاناً مميزاً</small>
            </div>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة الوجهات
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $destinations->total() }} وجهة</span>
            </div>

            @if($destinations->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 80px;">الصورة</th>
                                <th scope="col">الوجهة</th>
                                <th scope="col">الدولة</th>
                                <th scope="col" class="text-center">عدد الأنشطة</th>
                                <th scope="col">تاريخ الإضافة</th>
                                <th scope="col" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($destinations as $destination)
                                <tr>
                                    <td>
                                        @if($destination->image)
                                            <img src="{{ asset('storage/' . $destination->image) }}"
                                                 alt="{{ $destination->name }}"
                                                 class="rounded-3"
                                                 style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #e2e8f0; transition: all 0.3s ease;">
                                        @else
                                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                                 style="width: 70px; height: 70px; background: linear-gradient(135deg, #f0f0f0, #e0e0e0);">
                                                <i class="bi bi-image text-muted" style="font-size: 24px;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold mb-1">{{ $destination->name }}</div>
                                        @if($destination->description)
                                            <div class="text-muted small" style="max-width: 350px;">
                                                {{ Str::limit($destination->description, 60) }}
                                            </div>
                                        @endif
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $destination->updated_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info fs-6">
                                            <i class="bi bi-globe"></i> {{ $destination->country }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary fs-6">
                                            <i class="bi bi-activity"></i> {{ $destination->activities_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $destination->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $destination->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.destinations.show', $destination) }}"
                                               class="btn btn-sm btn-outline-secondary" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.destinations.edit', $destination) }}"
                                               class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.destinations.destroy', $destination) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الوجهة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($destinations->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $destinations->firstItem() ?? 0 }} إلى {{ $destinations->lastItem() ?? 0 }} من {{ $destinations->total() }} وجهة
                        </div>
                        <div>
                            {{ $destinations->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد وجهات</h5>
                    <p class="text-muted">لا توجد وجهات مضافة حتى الآن</p>
                    <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> إضافة وجهة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('head')
    <style>
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .table tbody tr:hover img {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
    @endpush
@endsection

