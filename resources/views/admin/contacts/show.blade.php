@extends('admin.layouts.app')

@section('title', 'تفاصيل الرسالة | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">لوحة التحكم</a></span>
        <span>›</span>
        <span><a href="{{ route('admin.contacts.index') }}" class="text-decoration-none">رسائل التواصل</a></span>
        <span>›</span>
        <span>تفاصيل الرسالة</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تفاصيل الرسالة 📧</h1>
            <p class="subtitle">معلومات كاملة عن رسالة التواصل</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
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
        <!-- معلومات الرسالة -->
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">معلومات الرسالة</h5>
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
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small">الموضوع</label>
                        <div class="fw-bold fs-5">{{ $contact->subject }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small">الرسالة</label>
                        <div class="p-4 bg-light rounded">
                            {{ $contact->message }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">تاريخ الإرسال</label>
                            <div>{{ $contact->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">آخر تحديث</label>
                            <div>{{ $contact->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات المرسل -->
        <div class="col-md-4">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">معلومات المرسل</h5>
                    <div class="mb-3">
                        <label class="text-muted small">الاسم</label>
                        <div class="fw-bold">{{ $contact->name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">البريد الإلكتروني</label>
                        <div>
                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                <i class="bi bi-envelope"></i> {{ $contact->email }}
                            </a>
                        </div>
                    </div>
                    @if($contact->phone)
                        <div class="mb-3">
                            <label class="text-muted small">الهاتف</label>
                            <div>
                                <a href="tel:{{ $contact->phone }}" class="text-decoration-none">
                                    <i class="bi bi-telephone"></i> {{ $contact->phone }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- إجراءات سريعة -->
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">إجراءات سريعة</h5>
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $contact->email }}?subject=رد على: {{ $contact->subject }}" 
                           class="btn btn-primary" target="_blank">
                            <i class="bi bi-envelope-paper"></i> الرد على الرسالة
                        </a>
                        @if($contact->phone)
                            <a href="tel:{{ $contact->phone }}" class="btn btn-outline-primary">
                                <i class="bi bi-telephone"></i> الاتصال
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تحديث حالة الرسالة -->
    <div class="card card-custom">
        <div class="card-body">
            <h5 class="fw-bold mb-3">تحديث حالة الرسالة</h5>
            <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="new" {{ $contact->status == 'new' ? 'selected' : '' }}>🔴 جديدة</option>
                        <option value="read" {{ $contact->status == 'read' ? 'selected' : '' }}>👁️ مقروءة</option>
                        <option value="replied" {{ $contact->status == 'replied' ? 'selected' : '' }}>✅ تم الرد</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> تحديث الحالة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
