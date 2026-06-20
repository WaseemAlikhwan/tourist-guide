@extends('admin.layouts.app')

@section('title', 'رسائل التواصل | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">لوحة التحكم</a></span>
        <span>›</span>
        <span>رسائل التواصل</span>
    </div>

    <div class="page-header">
        <div>
            <h1>رسائل التواصل 📧</h1>
            <p class="subtitle">عرض وإدارة رسائل التواصل من المستخدمين والزوار</p>
        </div>
    </div>

    @php
        $totalMessages = \App\Models\Contact::count();
        $newMessages = \App\Models\Contact::where('status', 'new')->count();
        $readMessages = \App\Models\Contact::where('status', 'read')->count();
        $repliedMessages = \App\Models\Contact::where('status', 'replied')->count();
    @endphp

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي الرسائل</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary">📧</span>
                </div>
                <div class="stat-number">{{ $totalMessages }}</div>
                <div class="stat-label">رسالة إجمالي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">جديدة</span>
                    <span class="badge rounded-pill bg-danger-subtle text-danger">🔴</span>
                </div>
                <div class="stat-number">{{ $newMessages }}</div>
                <div class="stat-label">رسالة جديدة</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">مقروءة</span>
                    <span class="badge rounded-pill bg-warning-subtle text-warning">👁️</span>
                </div>
                <div class="stat-number">{{ $readMessages }}</div>
                <div class="stat-label">رسالة مقروءة</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">تم الرد</span>
                    <span class="badge rounded-pill bg-success-subtle text-success">✅</span>
                </div>
                <div class="stat-number">{{ $repliedMessages }}</div>
                <div class="stat-label">رسالة تم الرد عليها</div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-funnel"></i> فلترة الرسائل
            </h5>
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">الحالة</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الرسائل</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>
                            🔴 جديدة
                        </option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>
                            👁️ مقروءة
                        </option>
                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>
                            ✅ تم الرد
                        </option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الرسائل -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة الرسائل
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $contacts->total() }} رسالة</span>
            </div>

            @if($contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 60px;">#</th>
                                <th scope="col">المرسل</th>
                                <th scope="col">البريد الإلكتروني</th>
                                <th scope="col">الهاتف</th>
                                <th scope="col">الموضوع</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">التاريخ</th>
                                <th scope="col" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr class="{{ $contact->status == 'new' ? 'table-warning' : '' }}">
                                    <td class="text-muted">{{ $contact->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $contact->name }}</div>
                                        @if($contact->status == 'new')
                                            <span class="badge bg-danger-subtle text-danger small">جديدة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                            <i class="bi bi-envelope"></i> {{ $contact->email }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($contact->phone)
                                            <a href="tel:{{ $contact->phone }}" class="text-decoration-none">
                                                <i class="bi bi-telephone"></i> {{ $contact->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($contact->subject, 40) }}</div>
                                        <small class="text-muted">{{ Str::limit($contact->message, 50) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'new' => 'danger',
                                                'read' => 'warning',
                                                'replied' => 'success'
                                            ];
                                            $statusIcons = [
                                                'new' => '🔴',
                                                'read' => '👁️',
                                                'replied' => '✅'
                                            ];
                                            $statusLabels = [
                                                'new' => 'جديدة',
                                                'read' => 'مقروءة',
                                                'replied' => 'تم الرد'
                                            ];
                                            $color = $statusColors[$contact->status] ?? 'secondary';
                                            $icon = $statusIcons[$contact->status] ?? '';
                                            $label = $statusLabels[$contact->status] ?? $contact->status;
                                        @endphp
                                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }} fs-6">
                                            {{ $icon }} {{ $label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $contact->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.contacts.show', $contact) }}" 
                                               class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.contacts.destroy', $contact) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')" title="حذف">
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
                @if($contacts->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $contacts->firstItem() ?? 0 }} إلى {{ $contacts->lastItem() ?? 0 }} من {{ $contacts->total() }} رسالة
                        </div>
                        <div>
                            {{ $contacts->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-envelope-x" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد رسائل</h5>
                    <p class="text-muted">لا توجد رسائل مطابقة لمعايير البحث المحددة</p>
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
        .table-warning {
            background-color: #fff3cd !important;
        }
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
    </style>
    @endpush
@endsection




