@extends('admin.layouts.app')

@section('title', 'تعديل كوبون | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">لوحة التحكم</a></span>
        <span>›</span>
        <span><a href="{{ route('admin.coupons.index') }}" class="text-decoration-none">الكوبونات</a></span>
        <span>›</span>
        <span>تعديل كوبون</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تعديل كوبون: {{ $coupon->code }} 🎫</h1>
            <p class="subtitle">تحديث معلومات الكوبون</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-primary">
                <i class="bi bi-eye"></i> عرض التفاصيل
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" id="couponForm">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-info-circle"></i> معلومات الكوبون الأساسية
                        </h5>

                        <div class="mb-4">
                            <label for="code" class="form-label fw-semibold">
                                كود الكوبون <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('code') is-invalid @enderror"
                                   id="code"
                                   name="code"
                                   value="{{ old('code', $coupon->code) }}"
                                   placeholder="مثال: SUMMER2024"
                                   required
                                   style="font-family: 'Courier New', monospace; font-weight: 700; text-transform: uppercase;">
                            <small class="text-muted">يجب أن يكون الكود فريداً وواضحاً (أحرف إنجليزية وأرقام فقط)</small>
                            @error('code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="وصف الكوبون والعروض الخاصة...">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="discount_type" class="form-label fw-semibold">
                                        نوع الخصم <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('discount_type') is-invalid @enderror"
                                            id="discount_type"
                                            name="discount_type"
                                            required>
                                        <option value="">اختر نوع الخصم</option>
                                        <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>
                                            نسبة مئوية (%)
                                        </option>
                                        <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>
                                            قيمة ثابتة (ل.س)
                                        </option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="discount_value" class="form-label fw-semibold">
                                        قيمة الخصم <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('discount_value') is-invalid @enderror"
                                               id="discount_value"
                                               name="discount_value"
                                               value="{{ old('discount_value', $coupon->discount_value) }}"
                                               required>
                                        <span class="input-group-text" id="discount_unit">{{ $coupon->discount_type == 'percentage' ? '%' : 'ل.س' }}</span>
                                    </div>
                                    <small class="text-muted">
                                        <span id="discount_hint">{{ $coupon->discount_type == 'percentage' ? 'النسبة المئوية: من 0 إلى 100' : 'القيمة الثابتة بالليرة السورية' }}</span>
                                    </small>
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="minimum_purchase" class="form-label fw-semibold">الحد الأدنى للشراء</label>
                            <div class="input-group">
                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       class="form-control @error('minimum_purchase') is-invalid @enderror"
                                       id="minimum_purchase"
                                       name="minimum_purchase"
                                       value="{{ old('minimum_purchase', $coupon->minimum_purchase) }}"
                                       placeholder="0">
                                <span class="input-group-text">ليرة سورية</span>
                            </div>
                            <small class="text-muted">المبلغ الأدنى الذي يجب أن يصل إليه الطلب ليستخدم الكوبون (اختياري)</small>
                            @error('minimum_purchase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-calendar"></i> فترة الصلاحية
                        </h5>

                        <div class="mb-4">
                            <label for="valid_from" class="form-label fw-semibold">
                                تاريخ البدء <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('valid_from') is-invalid @enderror"
                                   id="valid_from"
                                   name="valid_from"
                                   value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}"
                                   required>
                            @error('valid_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="valid_until" class="form-label fw-semibold">
                                تاريخ الانتهاء <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('valid_until') is-invalid @enderror"
                                   id="valid_until"
                                   name="valid_until"
                                   value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d')) }}"
                                   required>
                            @error('valid_until')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="usage_limit" class="form-label fw-semibold">حد الاستخدام</label>
                            <input type="number"
                                   min="1"
                                   class="form-control @error('usage_limit') is-invalid @enderror"
                                   id="usage_limit"
                                   name="usage_limit"
                                   value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                   placeholder="لا محدود">
                            <small class="text-muted">
                                عدد المرات المسموح استخدام الكوبون (اتركه فارغاً للاستخدام غير المحدود)
                                <br>
                                <strong>الاستخدام الحالي: {{ $coupon->usage_count }}</strong>
                            </small>
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('is_active') is-invalid @enderror"
                                       type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    تفعيل الكوبون
                                </label>
                            </div>
                            <small class="text-muted">يمكنك تفعيل أو تعطيل الكوبون</small>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light border-0 p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-eye"></i> معاينة الكوبون
                            </h6>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div>
                                    <span class="text-muted small">الكود:</span>
                                    <code id="preview_code" class="bg-white p-2 rounded-2 border" style="font-size: 18px; font-weight: 700;">
                                        {{ $coupon->code }}
                                    </code>
                                </div>
                                <div>
                                    <span class="text-muted small">الخصم:</span>
                                    <span id="preview_discount" class="fw-bold fs-5 text-success">
                                        {{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : $coupon->discount_value . ' ل.س' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-muted small">الحد الأدنى:</span>
                                    <span id="preview_minimum" class="fw-semibold">
                                        {{ $coupon->minimum_purchase ? $coupon->minimum_purchase . ' ل.س' : 'لا يوجد' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // تحديث وحدة الخصم حسب النوع
        document.getElementById('discount_type').addEventListener('change', function() {
            const unit = this.value === 'percentage' ? '%' : 'ل.س';
            const hint = this.value === 'percentage' 
                ? 'النسبة المئوية: من 0 إلى 100' 
                : 'القيمة الثابتة بالليرة السورية';
            
            document.getElementById('discount_unit').textContent = unit;
            document.getElementById('discount_hint').textContent = hint;
            updatePreview();
        });

        // تحديث معاينة الكوبون
        function updatePreview() {
            const code = document.getElementById('code').value || 'COUPON_CODE';
            const discountType = document.getElementById('discount_type').value;
            const discountValue = document.getElementById('discount_value').value || '0';
            const minimum = document.getElementById('minimum_purchase').value || '0';

            document.getElementById('preview_code').textContent = code.toUpperCase();
            
            if (discountValue > 0 && discountType) {
                const discount = discountType === 'percentage' 
                    ? `${discountValue}%` 
                    : `${discountValue} ل.س`;
                document.getElementById('preview_discount').textContent = discount;
            } else {
                document.getElementById('preview_discount').textContent = '-';
            }

            document.getElementById('preview_minimum').textContent = minimum > 0 
                ? `${minimum} ل.س` 
                : 'لا يوجد';
        }

        // تحديث المعاينة عند تغيير القيم
        ['code', 'discount_type', 'discount_value', 'minimum_purchase'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            }
        });

        // التحقق من صحة التاريخ
        document.getElementById('valid_from').addEventListener('change', function() {
            const validUntil = document.getElementById('valid_until');
            if (validUntil.value && validUntil.value < this.value) {
                alert('تاريخ الانتهاء يجب أن يكون بعد تاريخ البدء');
                validUntil.value = '';
            }
            validUntil.min = this.value;
        });

        // تحديث المعاينة عند التحميل
        updatePreview();
    </script>
    @endpush
@endsection
