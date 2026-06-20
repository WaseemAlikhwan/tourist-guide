@extends('admin.layouts.app')

@section('title', 'المستخدمين | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span>المستخدمين</span>
    </div>

    <div class="page-header">
        <div>
            <h1>المستخدمين 👥</h1>
            <p class="subtitle">إدارة جميع المستخدمين المسجلين في المنصة</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="primary-action d-flex align-items-center gap-3 text-decoration-none">
            <span class="icon">＋</span>
            <div class="text-end">
                <div class="fw-bold">إضافة مستخدم</div>
                <small class="text-white-50">إنشاء حساب جديد</small>
            </div>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $totalUsers = \App\Models\User::count();
        $adminUsers = \App\Models\User::where('role', 'admin')->count();
        $regularUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $usersWithBookings = \App\Models\User::has('bookings')->count();
    @endphp

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي المستخدمين</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary">👥</span>
                </div>
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">مستخدم إجمالي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">المدراء</span>
                    <span class="badge rounded-pill bg-danger-subtle text-danger">👑</span>
                </div>
                <div class="stat-number">{{ $adminUsers }}</div>
                <div class="stat-label">مدير</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">المستخدمين العاديين</span>
                    <span class="badge rounded-pill bg-success-subtle text-success">👤</span>
                </div>
                <div class="stat-number">{{ $regularUsers }}</div>
                <div class="stat-label">مستخدم عادي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">لديهم حجوزات</span>
                    <span class="badge rounded-pill bg-info-subtle text-info">📅</span>
                </div>
                <div class="stat-number">{{ $usersWithBookings }}</div>
                <div class="stat-label">مستخدم لديه حجوزات</div>
            </div>
        </div>
    </div>

    <!-- جدول المستخدمين -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة المستخدمين
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $users->total() }} مستخدم</span>
            </div>

            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 60px;">#</th>
                                <th scope="col">الاسم</th>
                                <th scope="col">البريد الإلكتروني</th>
                                <th scope="col" class="text-center">الدور</th>
                                <th scope="col" class="text-center">الحجوزات</th>
                                <th scope="col">تاريخ التسجيل</th>
                                <th scope="col" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $user->email }}</div>
                                        @if($user->email_verified_at)
                                            <small class="text-success">
                                                <i class="bi bi-check-circle-fill"></i> مفعّل
                                            </small>
                                        @else
                                            <small class="text-warning">
                                                <i class="bi bi-clock"></i> غير مفعّل
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger-subtle text-danger fs-6">
                                                <i class="bi bi-shield-check"></i> مدير
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary fs-6">
                                                <i class="bi bi-person"></i> مستخدم
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-subtle text-info fs-6">
                                            <i class="bi bi-calendar-check"></i> {{ $user->bookings_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $user->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-sm btn-outline-secondary" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($user->role !== 'admin')
                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $users->firstItem() ?? 0 }} إلى {{ $users->lastItem() ?? 0 }} من {{ $users->total() }} مستخدم
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-people" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا يوجد مستخدمين</h5>
                    <p class="text-muted">لا يوجد مستخدمين مسجلين حالياً</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> إضافة مستخدم جديد
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
        .card-custom .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            margin: 8px 0;
        }
        .stat-label {
            color: var(--text-light);
            font-size: 14px;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 600;
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

