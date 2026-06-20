@extends('admin.layouts.app')

@section('title', 'أساسيات السفر')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">أساسيات السفر</h1>
        <a href="{{ route('admin.travel-basics.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> إضافة أساسيات جديدة
        </a>
    </div>

    {{-- رسائل الفلاش --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($travelBasics->count())
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الأيقونة</th>
                            <th>النوع</th>
                            <th>الترتيب</th>
                            <th>الحالة</th>
                            <th>تاريخ الإضافة</th>
                            <th class="text-center">التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($travelBasics as $basic)
                            <tr>
                                <td>{{ $loop->iteration + ($travelBasics->currentPage() - 1) * $travelBasics->perPage() }}</td>
                                <td>
                                    <strong>{{ $basic->title }}</strong>
                                </td>
                                <td>
                                    @if($basic->icon)
                                        <i class="{{ $basic->icon }}" style="font-size: 1.5rem; color: var(--primary);"></i>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($basic->items && count($basic->items) > 0)
                                        <span class="badge bg-info">قائمة ({{ count($basic->items) }} عنصر)</span>
                                    @elseif($basic->content)
                                        <span class="badge bg-secondary">نص</span>
                                    @else
                                        <span class="badge bg-warning">فارغ</span>
                                    @endif
                                </td>
                                <td>{{ $basic->order }}</td>
                                <td>
                                    @if($basic->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> نشط
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times-circle"></i> غير نشط
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $basic->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.travel-basics.show', $basic) }}" class="btn btn-sm btn-outline-info" title="عرض التفاصيل">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.travel-basics.edit', $basic) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.travel-basics.destroy', $basic) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $travelBasics->links() }}
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-suitcase-rolling" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h5 class="text-muted">لا توجد أساسيات سفر حالياً</h5>
                <p class="text-muted">ابدأ بإضافة أساسيات السفر الأولى</p>
                <a href="{{ route('admin.travel-basics.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> إضافة أساسيات جديدة
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

