@extends('admin.layouts.app')

@section('title', 'تعديل وجهة | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.destinations.index') }}" class="text-decoration-none">الوجهات</a></span>
        <span>›</span>
        <span>تعديل وجهة</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تعديل وجهة: {{ $destination->name }} 🧭</h1>
            <p class="subtitle">تحديث معلومات الوجهة السياحية</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> العودة للقائمة
        </a>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="name_ar" class="form-label fw-semibold">اسم الوجهة (AR) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name_ar') is-invalid @enderror" 
                                   id="name_ar" 
                                   name="name_ar" 
                                   value="{{ old('name_ar', $destination->name_ar) }}" 
                                   required>
                            @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name_en" class="form-label fw-semibold">Destination Name (EN)</label>
                            <input type="text" 
                                   class="form-control @error('name_en') is-invalid @enderror" 
                                   id="name_en" 
                                   name="name_en" 
                                   value="{{ old('name_en', $destination->name_en) }}">
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="country_ar" class="form-label fw-semibold">الدولة (AR) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('country_ar') is-invalid @enderror" 
                                   id="country_ar" 
                                   name="country_ar" 
                                   value="{{ old('country_ar', $destination->country_ar) }}" 
                                   required>
                            @error('country_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="country_en" class="form-label fw-semibold">Country (EN)</label>
                            <input type="text" 
                                   class="form-control @error('country_en') is-invalid @enderror" 
                                   id="country_en" 
                                   name="country_en" 
                                   value="{{ old('country_en', $destination->country_en) }}">
                            @error('country_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description_ar" class="form-label fw-semibold">الوصف (AR)</label>
                            <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                      id="description_ar" 
                                      name="description_ar" 
                                      rows="6">{{ old('description_ar', $destination->description_ar) }}</textarea>
                            @error('description_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description_en" class="form-label fw-semibold">Description (EN)</label>
                            <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                      id="description_en" 
                                      name="description_en" 
                                      rows="6">{{ old('description_en', $destination->description_en) }}</textarea>
                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="latitude" class="form-label fw-semibold">خط العرض (Latitude)</label>
                                    <input type="number" 
                                           step="any"
                                           class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" 
                                           name="latitude" 
                                           value="{{ old('latitude', $destination->latitude) }}" 
                                           placeholder="مثال: 24.7136">
                                    <small class="text-muted">اختياري - للإشارات الجغرافية</small>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="longitude" class="form-label fw-semibold">خط الطول (Longitude)</label>
                                    <input type="number" 
                                           step="any"
                                           class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" 
                                           name="longitude" 
                                           value="{{ old('longitude', $destination->longitude) }}" 
                                           placeholder="مثال: 46.6753">
                                    <small class="text-muted">اختياري - للإشارات الجغرافية</small>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                            <label for="image" class="form-label fw-semibold">صورة الوجهة</label>
                            @if($destination->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $destination->image) }}" 
                                         alt="{{ $destination->name }}" 
                                         style="width: 100%; border-radius: 12px; max-height: 300px; object-fit: cover; border: 2px solid #ddd;">
                                    <small class="text-muted d-block mt-2">الصورة الحالية</small>
                                </div>
                            @endif
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
                                     alt="معاينة الصورة الجديدة" 
                                     style="display: none; width: 100%; border-radius: 12px; max-height: 300px; object-fit: cover; border: 2px dashed #ddd;">
                            </div>
                            <small class="text-muted">اختر صورة جديدة لاستبدال الصورة الحالية (الحد الأقصى: 2MB)</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-secondary">
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

    // تحميل الأقسام المخصصة الموجودة
    document.addEventListener('DOMContentLoaded', function() {
        @php
            $existingSectionsAr = old('custom_sections_ar', $destination->custom_sections_ar ?? []);
            $existingSectionsEn = old('custom_sections_en', $destination->custom_sections_en ?? []);
            $maxSections = max(count($existingSectionsAr), count($existingSectionsEn));
        @endphp
        @if($maxSections > 0)
            @for($i = 0; $i < $maxSections; $i++)
                addCustomSection(
                    {!! json_encode($existingSectionsAr[$i]['title'] ?? '') !!},
                    {!! json_encode($existingSectionsAr[$i]['content'] ?? '') !!},
                    {!! json_encode($existingSectionsEn[$i]['title'] ?? '') !!},
                    {!! json_encode($existingSectionsEn[$i]['content'] ?? '') !!}
                );
            @endfor
        @endif
    });
</script>
@endpush

