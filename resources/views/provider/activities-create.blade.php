@extends('provider.layouts.app')

@section('title', 'إضافة نشاط')

@section('content')
    <div class="provider-page-header">
        <h1>إضافة نشاط أو فعالية</h1>
        <p>يمكنك إنشاء نشاط كامل التفاصيل. يُنشر مباشرة على الموقع بعد الحفظ.</p>
    </div>

    <div class="provider-panel p-4">
        <form action="{{ route('provider.activities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">اسم النشاط <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="name_en" class="form-label fw-semibold">اسم النشاط (EN)</label>
                        <input type="text"
                               class="form-control @error('name_en') is-invalid @enderror"
                               id="name_en"
                               name="name_en"
                               value="{{ old('name_en') }}"
                               dir="ltr"
                               placeholder="Activity name in English (optional)">
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="type" class="form-label fw-semibold">نوع النشاط <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('type') is-invalid @enderror"
                                       id="type"
                                       name="type"
                                       value="{{ old('type') }}"
                                       required>
                                @error('type')
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
                    <div class="mb-4">
                        <label for="type_en" class="form-label fw-semibold">نوع النشاط (EN)</label>
                        <input type="text"
                               class="form-control @error('type_en') is-invalid @enderror"
                               id="type_en"
                               name="type_en"
                               value="{{ old('type_en') }}"
                               dir="ltr"
                               placeholder="Activity type in English (optional)">
                        @error('type_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                <label for="location" class="form-label fw-semibold">الموقع (اختياري)</label>
                                <input type="text"
                                       class="form-control @error('location') is-invalid @enderror"
                                       id="location"
                                       name="location"
                                       value="{{ old('location') }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="location_en" class="form-label fw-semibold">الموقع (EN)</label>
                        <input type="text"
                               class="form-control @error('location_en') is-invalid @enderror"
                               id="location_en"
                               name="location_en"
                               value="{{ old('location_en') }}"
                               dir="ltr"
                               placeholder="Location in English (optional)">
                        @error('location_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">الوصف</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="6">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description_en" class="form-label fw-semibold">الوصف (EN)</label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror"
                                  id="description_en"
                                  name="description_en"
                                  rows="4"
                                  dir="ltr"
                                  placeholder="Description in English (optional)">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="highlights" class="form-label fw-semibold">المعالم المميزة</label>
                        <textarea class="form-control @error('highlights') is-invalid @enderror"
                                  id="highlights"
                                  name="highlights"
                                  rows="4"
                                  placeholder="اكتب المعالم المميزة للنشاط، كل معلم في سطر جديد">{{ old('highlights') }}</textarea>
                        @error('highlights')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">اكتب كل معلم في سطر منفصل</small>
                    </div>
                    <div class="mb-4">
                        <label for="highlights_en" class="form-label fw-semibold">المعالم المميزة (EN)</label>
                        <textarea class="form-control @error('highlights_en') is-invalid @enderror"
                                  id="highlights_en"
                                  name="highlights_en"
                                  rows="4"
                                  dir="ltr"
                                  placeholder="Write highlights, one item per line">{{ old('highlights_en') }}</textarea>
                        @error('highlights_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">اكتب كل معلم إنجليزي في سطر منفصل</small>
                    </div>

                    <div id="bookingDetails" style="display: none;">
                        <hr class="my-4">
                        <h5 class="mb-3 fw-bold">تفاصيل الحجز</h5>

                        <div class="mb-4">
                            <label for="whats_included" class="form-label fw-semibold">ما يشمله العرض</label>
                            <textarea class="form-control @error('whats_included') is-invalid @enderror"
                                      id="whats_included"
                                      name="whats_included"
                                      rows="4"
                                      placeholder="مثال: - النقل من وإلى الفندق&#10;- دليل سياحي محترف&#10;- وجبة غداء">{{ old('whats_included') }}</textarea>
                            @error('whats_included')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اكتب كل عنصر في سطر منفصل</small>
                        </div>
                        <div class="mb-4">
                            <label for="whats_included_en" class="form-label fw-semibold">ما يشمله العرض (EN)</label>
                            <textarea class="form-control @error('whats_included_en') is-invalid @enderror"
                                      id="whats_included_en"
                                      name="whats_included_en"
                                      rows="4"
                                      dir="ltr"
                                      placeholder="Example: - Hotel pickup&#10;- Guide&#10;- Lunch">{{ old('whats_included_en') }}</textarea>
                            @error('whats_included_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اكتب كل عنصر إنجليزي في سطر منفصل</small>
                        </div>

                        <div class="mb-4">
                            <label for="whats_not_included" class="form-label fw-semibold">ما لا يشمله العرض</label>
                            <textarea class="form-control @error('whats_not_included') is-invalid @enderror"
                                      id="whats_not_included"
                                      name="whats_not_included"
                                      rows="4"
                                      placeholder="مثال: - المشروبات&#10;- التذاكر الشخصية&#10;- النفقات الشخصية">{{ old('whats_not_included') }}</textarea>
                            @error('whats_not_included')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اكتب كل عنصر في سطر منفصل</small>
                        </div>
                        <div class="mb-4">
                            <label for="whats_not_included_en" class="form-label fw-semibold">ما لا يشمله العرض (EN)</label>
                            <textarea class="form-control @error('whats_not_included_en') is-invalid @enderror"
                                      id="whats_not_included_en"
                                      name="whats_not_included_en"
                                      rows="4"
                                      dir="ltr"
                                      placeholder="Example: - Drinks&#10;- Personal tickets">{{ old('whats_not_included_en') }}</textarea>
                            @error('whats_not_included_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اكتب كل عنصر إنجليزي في سطر منفصل</small>
                        </div>

                        <div class="mb-4">
                            <label for="additional_info" class="form-label fw-semibold">معلومات إضافية</label>
                            <textarea class="form-control @error('additional_info') is-invalid @enderror"
                                      id="additional_info"
                                      name="additional_info"
                                      rows="4"
                                      placeholder="معلومات إضافية مهمة للزوار">{{ old('additional_info') }}</textarea>
                            @error('additional_info')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="additional_info_en" class="form-label fw-semibold">معلومات إضافية (EN)</label>
                            <textarea class="form-control @error('additional_info_en') is-invalid @enderror"
                                      id="additional_info_en"
                                      name="additional_info_en"
                                      rows="4"
                                      dir="ltr"
                                      placeholder="Additional important information">{{ old('additional_info_en') }}</textarea>
                            @error('additional_info_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_policy" class="form-label fw-semibold">سياسة الدفع</label>
                            <textarea class="form-control @error('payment_policy') is-invalid @enderror"
                                      id="payment_policy"
                                      name="payment_policy"
                                      rows="4"
                                      placeholder="مثال: - الدفع نقداً عند الوصول&#10;- أو الدفع عبر البطاقة">{{ old('payment_policy') }}</textarea>
                            @error('payment_policy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="payment_policy_en" class="form-label fw-semibold">سياسة الدفع (EN)</label>
                            <textarea class="form-control @error('payment_policy_en') is-invalid @enderror"
                                      id="payment_policy_en"
                                      name="payment_policy_en"
                                      rows="4"
                                      dir="ltr"
                                      placeholder="Payment policy in English">{{ old('payment_policy_en') }}</textarea>
                            @error('payment_policy_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="cancellation_policy" class="form-label fw-semibold">سياسة الإلغاء</label>
                            <textarea class="form-control @error('cancellation_policy') is-invalid @enderror"
                                      id="cancellation_policy"
                                      name="cancellation_policy"
                                      rows="4"
                                      placeholder="مثال: - يمكن الإلغاء مجاناً قبل 24 ساعة&#10;- بعد ذلك يتم خصم 50% من المبلغ">{{ old('cancellation_policy') }}</textarea>
                            @error('cancellation_policy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="cancellation_policy_en" class="form-label fw-semibold">سياسة الإلغاء (EN)</label>
                            <textarea class="form-control @error('cancellation_policy_en') is-invalid @enderror"
                                      id="cancellation_policy_en"
                                      name="cancellation_policy_en"
                                      rows="4"
                                      dir="ltr"
                                      placeholder="Cancellation policy in English">{{ old('cancellation_policy_en') }}</textarea>
                            @error('cancellation_policy_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 fw-bold">الأقسام المخصصة</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCustomSection()">
                                <i class="bi bi-plus-circle"></i> إضافة قسم جديد
                            </button>
                        </div>
                        <div id="customSectionsContainer"></div>
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

                            <div class="alert alert-light border small mb-3">
                                خيارات "نشاط مميز" و"معلم يجب زيارته" يتم تحديدها من قبل الإدارة فقط.
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
                <a href="{{ route('provider.activities.index') }}" class="btn btn-outline-secondary">
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> حفظ ونشر النشاط
                </button>
            </div>
        </form>
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
        bookingDetails.style.display = requiresBooking ? 'block' : 'none';
    }

    function toggleEventFields() {
        const isEvent = document.getElementById('is_event').checked;
        const eventDateField = document.getElementById('eventDateField');
        const eventDateInput = document.getElementById('event_date');
        const eventDateDisplay = document.getElementById('eventDateDisplay');

        if (isEvent) {
            eventDateField.style.display = 'block';
            eventDateInput.setAttribute('required', 'required');
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
            eventDateText.textContent = date.toLocaleDateString('ar-SA', options);
            eventDateDisplay.style.display = 'block';
        } else {
            eventDateDisplay.style.display = 'none';
        }
    }

    let customSectionCounter = 0;

    function addCustomSection(title = '', content = '') {
        const container = document.getElementById('customSectionsContainer');
        const sectionId = customSectionCounter++;

        const card = document.createElement('div');
        card.className = 'card mb-3 custom-section-item';
        card.setAttribute('data-section-id', sectionId);

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';

        const header = document.createElement('div');
        header.className = 'd-flex justify-content-between align-items-center mb-3';

        const titleHeader = document.createElement('h6');
        titleHeader.className = 'mb-0 fw-semibold';
        titleHeader.textContent = 'قسم جديد';

        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'btn btn-sm btn-outline-danger';
        deleteBtn.innerHTML = '<i class="bi bi-trash"></i> حذف';
        deleteBtn.onclick = function() { removeCustomSection(sectionId); };

        header.appendChild(titleHeader);
        header.appendChild(deleteBtn);

        const titleGroup = document.createElement('div');
        titleGroup.className = 'mb-3';
        titleGroup.innerHTML = `
            <label class="form-label fw-semibold">عنوان القسم</label>
            <input type="text" class="form-control custom-section-title" name="custom_sections_ar[${sectionId}][title]" value="${title}" placeholder="مثال: معلومات إضافية، نصائح مهمة، إلخ">
        `;

        const contentGroup = document.createElement('div');
        contentGroup.className = 'mb-3';
        contentGroup.innerHTML = `
            <label class="form-label fw-semibold">محتوى القسم</label>
            <textarea class="form-control custom-section-content" name="custom_sections_ar[${sectionId}][content]" rows="4" placeholder="اكتب محتوى القسم هنا...">${content}</textarea>
        `;

        cardBody.appendChild(header);
        cardBody.appendChild(titleGroup);
        cardBody.appendChild(contentGroup);
        card.appendChild(cardBody);
        container.appendChild(card);
    }

    function removeCustomSection(sectionId) {
        const section = document.querySelector(`.custom-section-item[data-section-id="${sectionId}"]`);
        if (section) section.remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleBookingDetails();
        toggleEventFields();

        @if(old('custom_sections_ar'))
            @foreach(old('custom_sections_ar', []) as $section)
                addCustomSection({!! json_encode($section['title'] ?? '') !!}, {!! json_encode($section['content'] ?? '') !!});
            @endforeach
        @endif
    });
</script>
@endpush
