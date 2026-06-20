@extends('admin.layouts.app')

@section('title', 'إضافة نشاط جديد | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.activities.index') }}" class="text-decoration-none">الأنشطة</a></span>
        <span>›</span>
        <span>إضافة نشاط جديد</span>
    </div>

    <div class="page-header">
        <div>
            <h1>إضافة نشاط جديد 🎯</h1>
            <p class="subtitle">أضف نشاطاً مرتبطاً بإحدى الوجهات السياحية</p>
        </div>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> العودة للقائمة
        </a>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="name_ar" class="form-label fw-semibold">اسم النشاط (AR) <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name_ar') is-invalid @enderror"
                                   id="name_ar"
                                   name="name_ar"
                                   value="{{ old('name_ar') }}"
                                   required>
                            @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="name_en" class="form-label fw-semibold">Activity Name (EN)</label>
                            <input type="text"
                                   class="form-control @error('name_en') is-invalid @enderror"
                                   id="name_en"
                                   name="name_en"
                                   value="{{ old('name_en') }}">
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="destination_id" class="form-label fw-semibold">الوجهة <span class="text-danger">*</span></label>
                            <select class="form-select @error('destination_id') is-invalid @enderror"
                                    id="destination_id"
                                    name="destination_id"
                                    required>
                                <option value="">اختر الوجهة</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="provider_id" class="form-label fw-semibold">مزوّد المحتوى (اختياري)</label>
                            <select class="form-select @error('provider_id') is-invalid @enderror"
                                    id="provider_id"
                                    name="provider_id">
                                <option value="">— بدون مزوّد —</option>
                                @foreach($providers as $p)
                                    <option value="{{ $p->id }}" {{ old('provider_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">يُربط أرباح الحجوزات المدفوعة بهذا المزوّد بعد اعتماده في المنصة.</div>
                            @error('provider_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="provider_review_status" class="form-label fw-semibold">حالة مراجعة المزوّد</label>
                            <select class="form-select @error('provider_review_status') is-invalid @enderror"
                                    id="provider_review_status"
                                    name="provider_review_status">
                                <option value="approved" {{ old('provider_review_status', 'approved') === 'approved' ? 'selected' : '' }}>معتمد</option>
                                <option value="pending_review" {{ old('provider_review_status') === 'pending_review' ? 'selected' : '' }}>قيد المراجعة</option>
                                <option value="rejected" {{ old('provider_review_status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
                            </select>
                            @error('provider_review_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="type_ar" class="form-label fw-semibold">نوع النشاط (AR) <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('type_ar') is-invalid @enderror"
                                           id="type_ar"
                                           name="type_ar"
                                           value="{{ old('type_ar') }}"
                                           required>
                                    @error('type_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="type_en" class="form-label fw-semibold">Activity Type (EN)</label>
                                    <input type="text"
                                           class="form-control @error('type_en') is-invalid @enderror"
                                           id="type_en"
                                           name="type_en"
                                           value="{{ old('type_en') }}">
                                    @error('type_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="price" class="form-label fw-semibold">السعر (اختياري)</label>
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price') }}"
                                           placeholder="سيظهر فقط إذا كان يتطلب حجز">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">سيظهر السعر فقط إذا كان النشاط يتطلب حجز</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="rating" class="form-label fw-semibold">التقييم (0 - 5)</label>
                                    <input type="number"
                                           step="0.1"
                                           min="0"
                                           max="5"
                                           class="form-control @error('rating') is-invalid @enderror"
                                           id="rating"
                                           name="rating"
                                           value="{{ old('rating') }}">
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="location_ar" class="form-label fw-semibold">الموقع (AR)</label>
                                    <input type="text"
                                           class="form-control @error('location_ar') is-invalid @enderror"
                                           id="location_ar"
                                           name="location_ar"
                                           value="{{ old('location_ar') }}">
                                    @error('location_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="location_en" class="form-label fw-semibold">Location (EN)</label>
                                    <input type="text"
                                           class="form-control @error('location_en') is-invalid @enderror"
                                           id="location_en"
                                           name="location_en"
                                           value="{{ old('location_en') }}">
                                    @error('location_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description_ar" class="form-label fw-semibold">الوصف (AR)</label>
                            <textarea class="form-control @error('description_ar') is-invalid @enderror"
                                      id="description_ar"
                                      name="description_ar"
                                      rows="6">{{ old('description_ar') }}</textarea>
                            @error('description_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description_en" class="form-label fw-semibold">Description (EN)</label>
                            <textarea class="form-control @error('description_en') is-invalid @enderror"
                                      id="description_en"
                                      name="description_en"
                                      rows="6">{{ old('description_en') }}</textarea>
                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="highlights" class="form-label fw-semibold">المعالم المميزة</label>
                            <textarea class="form-control @error('highlights') is-invalid @enderror"
                                      id="highlights"
                                      name="highlights_ar"
                                      rows="4"
                                      placeholder="اكتب المعالم المميزة للنشاط، كل معلم في سطر جديد">{{ old('highlights_ar') }}</textarea>
                            @error('highlights_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اكتب كل معلم في سطر منفصل</small>
                        </div>
                        <div class="mb-4">
                            <label for="highlights_en" class="form-label fw-semibold">Highlights (EN)</label>
                            <textarea class="form-control @error('highlights_en') is-invalid @enderror"
                                      id="highlights_en"
                                      name="highlights_en"
                                      rows="4"
                                      placeholder="Write highlights, one per line">{{ old('highlights_en') }}</textarea>
                            @error('highlights_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Write each highlight on a separate line</small>
                        </div>

                        <div id="bookingDetails" style="display: none;">
                            <hr class="my-4">
                            <h5 class="mb-3 fw-bold">تفاصيل الحجز</h5>

                            <div class="mb-4">
                                <label for="whats_included" class="form-label fw-semibold">ما يشمله العرض</label>
                                <textarea class="form-control @error('whats_included') is-invalid @enderror"
                                          id="whats_included"
                                          name="whats_included_ar"
                                          rows="4"
                                          placeholder="مثال: - النقل من وإلى الفندق&#10;- دليل سياحي محترف&#10;- وجبة غداء">{{ old('whats_included_ar') }}</textarea>
                                @error('whats_included_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">اكتب كل عنصر في سطر منفصل</small>
                            </div>
                            <div class="mb-4">
                                <label for="whats_included_en" class="form-label fw-semibold">What's Included (EN)</label>
                                <textarea class="form-control @error('whats_included_en') is-invalid @enderror"
                                          id="whats_included_en"
                                          name="whats_included_en"
                                          rows="4"
                                          placeholder="Example: - Hotel pickup and drop-off&#10;- Professional guide&#10;- Lunch meal">{{ old('whats_included_en') }}</textarea>
                                @error('whats_included_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Write each item on a separate line</small>
                            </div>

                            <div class="mb-4">
                                <label for="whats_not_included" class="form-label fw-semibold">ما لا يشمله العرض</label>
                                <textarea class="form-control @error('whats_not_included') is-invalid @enderror"
                                          id="whats_not_included"
                                          name="whats_not_included_ar"
                                          rows="4"
                                          placeholder="مثال: - المشروبات&#10;- التذاكر الشخصية&#10;- النفقات الشخصية">{{ old('whats_not_included_ar') }}</textarea>
                                @error('whats_not_included_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">اكتب كل عنصر في سطر منفصل</small>
                            </div>
                            <div class="mb-4">
                                <label for="whats_not_included_en" class="form-label fw-semibold">What's Not Included (EN)</label>
                                <textarea class="form-control @error('whats_not_included_en') is-invalid @enderror"
                                          id="whats_not_included_en"
                                          name="whats_not_included_en"
                                          rows="4"
                                          placeholder="Example: - Drinks&#10;- Personal tickets&#10;- Personal expenses">{{ old('whats_not_included_en') }}</textarea>
                                @error('whats_not_included_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Write each item on a separate line</small>
                            </div>

                            <div class="mb-4">
                                <label for="additional_info" class="form-label fw-semibold">معلومات إضافية</label>
                                <textarea class="form-control @error('additional_info') is-invalid @enderror"
                                          id="additional_info"
                                          name="additional_info_ar"
                                          rows="4"
                                          placeholder="معلومات إضافية مهمة للزوار">{{ old('additional_info_ar') }}</textarea>
                                @error('additional_info_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="additional_info_en" class="form-label fw-semibold">Additional Information (EN)</label>
                                <textarea class="form-control @error('additional_info_en') is-invalid @enderror"
                                          id="additional_info_en"
                                          name="additional_info_en"
                                          rows="4"
                                          placeholder="Important additional information for visitors">{{ old('additional_info_en') }}</textarea>
                                @error('additional_info_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="payment_policy" class="form-label fw-semibold">سياسة الدفع</label>
                                <textarea class="form-control @error('payment_policy') is-invalid @enderror"
                                          id="payment_policy"
                                          name="payment_policy_ar"
                                          rows="4"
                                          placeholder="مثال: - الدفع نقداً عند الوصول&#10;- أو الدفع عبر البطاقة">{{ old('payment_policy_ar') }}</textarea>
                                @error('payment_policy_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="payment_policy_en" class="form-label fw-semibold">Payment Policy (EN)</label>
                                <textarea class="form-control @error('payment_policy_en') is-invalid @enderror"
                                          id="payment_policy_en"
                                          name="payment_policy_en"
                                          rows="4"
                                          placeholder="Example: - Pay cash on arrival&#10;- Or pay by card">{{ old('payment_policy_en') }}</textarea>
                                @error('payment_policy_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="cancellation_policy" class="form-label fw-semibold">سياسة الإلغاء</label>
                                <textarea class="form-control @error('cancellation_policy') is-invalid @enderror"
                                          id="cancellation_policy"
                                          name="cancellation_policy_ar"
                                          rows="4"
                                          placeholder="مثال: - يمكن الإلغاء مجاناً قبل 24 ساعة&#10;- بعد ذلك يتم خصم 50% من المبلغ">{{ old('cancellation_policy_ar') }}</textarea>
                                @error('cancellation_policy_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="cancellation_policy_en" class="form-label fw-semibold">Cancellation Policy (EN)</label>
                                <textarea class="form-control @error('cancellation_policy_en') is-invalid @enderror"
                                          id="cancellation_policy_en"
                                          name="cancellation_policy_en"
                                          rows="4"
                                          placeholder="Example: - Free cancellation 24h before&#10;- Afterwards, 50% charge applies">{{ old('cancellation_policy_en') }}</textarea>
                                @error('cancellation_policy_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الأقسام المخصصة -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-bold">الأقسام المخصصة (AR/EN)</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCustomSection()">
                                    <i class="bi bi-plus-circle"></i> إضافة قسم جديد
                                </button>
                            </div>
                            <div id="customSectionsContainer">
                                <!-- سيتم إضافة الأقسام هنا ديناميكياً -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">صورة النشاط</label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img id="imagePreview"
                                     src="#"
                                     alt="معاينة الصورة"
                                     style="display: none; width: 100%; border-radius: 12px; max-height: 300px; object-fit: cover; border: 2px dashed #ddd;">
                            </div>
                            <small class="text-muted">الحد الأقصى لحجم الصورة: 2MB</small>
                        </div>

                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">خيارات إضافية</h6>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning"></i> نشاط مميز (الجولات والتجارب المميزة)
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_must_visit" name="is_must_visit" value="1" {{ old('is_must_visit') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_must_visit">
                                        <i class="fas fa-heart text-danger"></i> معلم يجب زيارته
                                    </label>
                                </div>

                                <hr class="my-3">

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_event" name="is_event" value="1" {{ old('is_event') ? 'checked' : '' }} onchange="toggleEventFields()">
                                    <label class="form-check-label" for="is_event">
                                        <i class="fas fa-calendar-alt text-info"></i> فعالية (تاريخ ثابت)
                                    </label>
                                    <small class="text-muted d-block mt-1">إذا كان مفعّل، يجب إدخال تاريخ الفعالية</small>
                                </div>

                                <div id="eventDateField" style="display: none;">
                                    <div class="mb-3">
                                        <label for="event_date" class="form-label fw-semibold small">تاريخ الفعالية <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control form-control-sm @error('event_date') is-invalid @enderror"
                                               id="event_date"
                                               name="event_date"
                                               value="{{ old('event_date') }}"
                                               min="{{ date('Y-m-d') }}"
                                               onchange="updateEventDateDisplay()">
                                        @error('event_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">تاريخ الفعالية الثابت</small>
                                        <div id="eventDateDisplay" class="mt-2 p-2 bg-light rounded" style="display: none;">
                                            <strong>تاريخ الفعالية:</strong> <span id="eventDateText"></span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="requires_booking" name="requires_booking" value="1" {{ old('requires_booking', true) ? 'checked' : '' }} onchange="toggleBookingDetails()">
                                    <label class="form-check-label" for="requires_booking">
                                        <i class="fas fa-calendar-check text-primary"></i> يتطلب حجز
                                    </label>
                                    <small class="text-muted d-block mt-1">إذا كان غير مفعّل، لن يظهر زر الحجز</small>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label for="duration_minutes" class="form-label fw-semibold small">المدة (بالدقائق)</label>
                                        <input type="number"
                                               class="form-control form-control-sm @error('duration_minutes') is-invalid @enderror"
                                               id="duration_minutes"
                                               name="duration_minutes"
                                               value="{{ old('duration_minutes') }}"
                                               min="0"
                                               placeholder="مثال: 120">
                                        @error('duration_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="duration_label" class="form-label fw-semibold small">تسمية المدة</label>
                                        <input type="text"
                                               class="form-control form-control-sm @error('duration_label') is-invalid @enderror"
                                               id="duration_label"
                                               name="duration_label"
                                               value="{{ old('duration_label') }}"
                                               placeholder="مثال: ساعتان">
                                        @error('duration_label')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">اختياري - سيتم عرضها بدلاً من الدقائق</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> إضافة النشاط
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    function toggleBookingDetails() {
        const requiresBooking = document.getElementById('requires_booking').checked;
        const bookingDetails = document.getElementById('bookingDetails');
        if (requiresBooking) {
            bookingDetails.style.display = 'block';
        } else {
            bookingDetails.style.display = 'none';
        }
    }

    function toggleEventFields() {
        const isEvent = document.getElementById('is_event').checked;
        const eventDateField = document.getElementById('eventDateField');
        const eventDateInput = document.getElementById('event_date');
        const eventDateDisplay = document.getElementById('eventDateDisplay');
        
        if (isEvent) {
            eventDateField.style.display = 'block';
            eventDateInput.setAttribute('required', 'required');
            // إذا كان هناك تاريخ محدد، اعرضه
            if (eventDateInput.value) {
                updateEventDateDisplay();
            }
        } else {
            eventDateField.style.display = 'none';
            eventDateInput.removeAttribute('required');
            eventDateInput.value = '';
            eventDateDisplay.style.display = 'none';
        }
    }

    function updateEventDateDisplay() {
        const eventDateInput = document.getElementById('event_date');
        const eventDateDisplay = document.getElementById('eventDateDisplay');
        const eventDateText = document.getElementById('eventDateText');
        
        if (eventDateInput.value) {
            const date = new Date(eventDateInput.value);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = date.toLocaleDateString('ar-SA', options);
            eventDateText.textContent = formattedDate;
            eventDateDisplay.style.display = 'block';
        } else {
            eventDateDisplay.style.display = 'none';
        }
    }

    // إدارة الأقسام المخصصة
    let customSectionCounter = 0;

    function addCustomSection(titleAr = '', contentAr = '', titleEn = '', contentEn = '') {
        const container = document.getElementById('customSectionsContainer');
        const sectionId = customSectionCounter++;
        
        // إنشاء العناصر
        const card = document.createElement('div');
        card.className = 'card mb-3 custom-section-item';
        card.setAttribute('data-section-id', sectionId);
        
        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';
        
        // رأس القسم
        const header = document.createElement('div');
        header.className = 'd-flex justify-content-between align-items-center mb-3';
        
        const titleHeader = document.createElement('h6');
        titleHeader.className = 'mb-0 fw-semibold';
        titleHeader.textContent = 'قسم جديد (AR/EN)';
        
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'btn btn-sm btn-outline-danger';
        deleteBtn.innerHTML = '<i class="bi bi-trash"></i> حذف';
        deleteBtn.onclick = function() { removeCustomSection(sectionId); };
        
        header.appendChild(titleHeader);
        header.appendChild(deleteBtn);
        
        // حقل العنوان AR
        const titleGroup = document.createElement('div');
        titleGroup.className = 'mb-3';
        
        const titleLabel = document.createElement('label');
        titleLabel.className = 'form-label fw-semibold';
        titleLabel.textContent = 'عنوان القسم (AR)';
        
        const titleInput = document.createElement('input');
        titleInput.type = 'text';
        titleInput.className = 'form-control custom-section-title';
        titleInput.name = `custom_sections_ar[${sectionId}][title]`;
        titleInput.value = titleAr;
        titleInput.placeholder = 'مثال: معلومات إضافية، نصائح مهمة، إلخ';
        
        titleGroup.appendChild(titleLabel);
        titleGroup.appendChild(titleInput);
        
        // حقل العنوان EN
        const titleGroupEn = document.createElement('div');
        titleGroupEn.className = 'mb-3';

        const titleLabelEn = document.createElement('label');
        titleLabelEn.className = 'form-label fw-semibold';
        titleLabelEn.textContent = 'Section Title (EN)';

        const titleInputEn = document.createElement('input');
        titleInputEn.type = 'text';
        titleInputEn.className = 'form-control custom-section-title';
        titleInputEn.name = `custom_sections_en[${sectionId}][title]`;
        titleInputEn.value = titleEn;
        titleInputEn.placeholder = 'Example: Extra Information, Important Tips...';

        titleGroupEn.appendChild(titleLabelEn);
        titleGroupEn.appendChild(titleInputEn);

        // حقل المحتوى AR
        const contentGroup = document.createElement('div');
        contentGroup.className = 'mb-3';
        
        const contentLabel = document.createElement('label');
        contentLabel.className = 'form-label fw-semibold';
        contentLabel.textContent = 'محتوى القسم (AR)';
        
        const contentTextarea = document.createElement('textarea');
        contentTextarea.className = 'form-control custom-section-content';
        contentTextarea.name = `custom_sections_ar[${sectionId}][content]`;
        contentTextarea.rows = 4;
        contentTextarea.placeholder = 'اكتب محتوى القسم هنا...';
        contentTextarea.textContent = contentAr;
        
        contentGroup.appendChild(contentLabel);
        contentGroup.appendChild(contentTextarea);

        // حقل المحتوى EN
        const contentGroupEn = document.createElement('div');
        contentGroupEn.className = 'mb-3';

        const contentLabelEn = document.createElement('label');
        contentLabelEn.className = 'form-label fw-semibold';
        contentLabelEn.textContent = 'Section Content (EN)';

        const contentTextareaEn = document.createElement('textarea');
        contentTextareaEn.className = 'form-control custom-section-content';
        contentTextareaEn.name = `custom_sections_en[${sectionId}][content]`;
        contentTextareaEn.rows = 4;
        contentTextareaEn.placeholder = 'Write section content in English...';
        contentTextareaEn.textContent = contentEn;

        contentGroupEn.appendChild(contentLabelEn);
        contentGroupEn.appendChild(contentTextareaEn);
        
        // تجميع العناصر
        cardBody.appendChild(header);
        cardBody.appendChild(titleGroup);
        cardBody.appendChild(titleGroupEn);
        cardBody.appendChild(contentGroup);
        cardBody.appendChild(contentGroupEn);
        card.appendChild(cardBody);
        container.appendChild(card);
    }

    function removeCustomSection(sectionId) {
        const section = document.querySelector(`.custom-section-item[data-section-id="${sectionId}"]`);
        if (section) {
            section.remove();
        }
    }

    // تفعيل/تعطيل عند التحميل
    document.addEventListener('DOMContentLoaded', function() {
        toggleBookingDetails();
        toggleEventFields();
        
        // إضافة الأقسام المخصصة المحفوظة مسبقاً (في حالة التعديل)
        @if(old('custom_sections_ar') || old('custom_sections_en'))
            @php
                $oldSectionsAr = old('custom_sections_ar', []);
                $oldSectionsEn = old('custom_sections_en', []);
                $maxSections = max(count($oldSectionsAr), count($oldSectionsEn));
            @endphp
            @for($i = 0; $i < $maxSections; $i++)
                addCustomSection(
                    {!! json_encode($oldSectionsAr[$i]['title'] ?? '') !!},
                    {!! json_encode($oldSectionsAr[$i]['content'] ?? '') !!},
                    {!! json_encode($oldSectionsEn[$i]['title'] ?? '') !!},
                    {!! json_encode($oldSectionsEn[$i]['content'] ?? '') !!}
                );
            @endfor
        @endif
    });
</script>
@endpush

