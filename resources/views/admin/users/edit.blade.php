@extends('admin.layouts.app')

@section('title', 'تعديل مستخدم | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.users.index') }}" class="text-decoration-none">المستخدمين</a></span>
        <span>›</span>
        <span>تعديل مستخدم</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تعديل مستخدم: {{ $user->name }} 👤</h1>
            <p class="subtitle">تحديث معلومات المستخدم</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> العودة للقائمة
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">كلمة المرور الجديدة</label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <small class="text-muted">اتركه فارغاً إذا لم ترد تغيير كلمة المرور</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-semibold">تأكيد كلمة المرور</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">الدور <span class="text-danger">*</span></label>
                            @if($user->role === 'admin')
                                <input type="text" class="form-control" value="مدير" disabled>
                                <input type="hidden" name="role" value="admin">
                                <small class="text-muted">لا يمكن تغيير صلاحية المدير</small>
                            @else
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">اختر الدور</option>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>مستخدم</option>
                                </select>
                                <small class="text-muted">لا يمكن تغيير صلاحية المستخدم إلى مدير</small>
                            @endif
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

