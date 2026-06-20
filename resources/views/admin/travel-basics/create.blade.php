@extends('admin.layouts.app')

@section('title', 'إضافة أساسيات جديدة | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.travel-basics.index') }}" class="text-decoration-none">أساسيات السفر</a></span>
        <span>›</span>
        <span>إضافة أساسيات جديدة</span>
    </div>

    <div class="page-header">
        <div>
            <h1>إضافة أساسيات جديدة 🧳</h1>
            <p class="subtitle">أضف معلومات جديدة لأساسيات السفر</p>
        </div>
        <a href="{{ route('admin.travel-basics.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> العودة للقائمة
        </a>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <form action="{{ route('admin.travel-basics.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="title_ar" class="form-label fw-semibold">العنوان (AR) <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title_ar') is-invalid @enderror"
                                   id="title_ar"
                                   name="title_ar"
                                   value="{{ old('title_ar') }}"
                                   required>
                            @error('title_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title_en" class="form-label fw-semibold">Title (EN)</label>
                            <input type="text"
                                   class="form-control @error('title_en') is-invalid @enderror"
                                   id="title_en"
                                   name="title_en"
                                   value="{{ old('title_en') }}">
                            @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="icon" class="form-label fw-semibold">الأيقونة (اختياري)</label>
                            <input type="text"
                                   class="form-control @error('icon') is-invalid @enderror"
                                   id="icon"
                                   name="icon"
                                   value="{{ old('icon') }}"
                                   placeholder="مثال: fas fa-passport, bi bi-briefcase">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">يمكنك استخدام أيقونات Font Awesome أو Bootstrap Icons</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">نوع المحتوى</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="content_type" id="content_type_list" value="list" {{ old('content_type', 'list') == 'list' ? 'checked' : '' }} onchange="toggleContentType()">
                                <label class="btn btn-outline-primary" for="content_type_list">
                                    <i class="bi bi-list-ul"></i> قائمة عناصر
                                </label>

                                <input type="radio" class="btn-check" name="content_type" id="content_type_text" value="text" {{ old('content_type') == 'text' ? 'checked' : '' }} onchange="toggleContentType()">
                                <label class="btn btn-outline-primary" for="content_type_text">
                                    <i class="bi bi-file-text"></i> نص حر
                                </label>
                            </div>
                        </div>

                        <!-- محتوى القائمة -->
                        <div id="listContent" style="display: {{ old('content_type', 'list') == 'list' ? 'block' : 'none' }};">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">العناصر (AR/EN)</label>
                                <div id="itemsContainer">
                                    <!-- سيتم إضافة العناصر هنا ديناميكياً -->
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addItem()">
                                    <i class="bi bi-plus-circle"></i> إضافة عنصر جديد
                                </button>
                            </div>
                        </div>

                        <!-- محتوى النص -->
                        <div id="textContent" style="display: {{ old('content_type') == 'text' ? 'block' : 'none' }};">
                            <div class="mb-4">
                                <label for="content_ar" class="form-label fw-semibold">المحتوى (AR)</label>
                                <textarea class="form-control @error('content_ar') is-invalid @enderror"
                                          id="content_ar"
                                          name="content_ar"
                                          rows="8">{{ old('content_ar') }}</textarea>
                                @error('content_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="content_en" class="form-label fw-semibold">Content (EN)</label>
                                <textarea class="form-control @error('content_en') is-invalid @enderror"
                                          id="content_en"
                                          name="content_en"
                                          rows="8">{{ old('content_en') }}</textarea>
                                @error('content_en')
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
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">خيارات إضافية</h6>
                                
                                <div class="mb-4">
                                    <label for="order" class="form-label fw-semibold">الترتيب</label>
                                    <input type="number"
                                           class="form-control @error('order') is-invalid @enderror"
                                           id="order"
                                           name="order"
                                           value="{{ old('order', 0) }}"
                                           min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">يتم ترتيب العناصر حسب هذا الرقم (الأصغر أولاً)</small>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle text-success"></i> نشط
                                    </label>
                                    <small class="text-muted d-block mt-1">سيظهر في الموقع للزوار</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('admin.travel-basics.index') }}" class="btn btn-outline-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> إضافة الأساسيات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // إدارة نوع المحتوى
    function toggleContentType() {
        const contentType = document.querySelector('input[name="content_type"]:checked').value;
        const listContent = document.getElementById('listContent');
        const textContent = document.getElementById('textContent');
        
        if (contentType === 'list') {
            listContent.style.display = 'block';
            textContent.style.display = 'none';
        } else {
            listContent.style.display = 'none';
            textContent.style.display = 'block';
        }
    }

    // إدارة العناصر
    let itemCounter = 0;

    function addItem(valueAr = '', valueEn = '') {
        const container = document.getElementById('itemsContainer');
        const itemId = itemCounter++;
        
        const itemGroup = document.createElement('div');
        itemGroup.className = 'row g-2 mb-2 item-group';
        itemGroup.setAttribute('data-item-id', itemId);
        
        const arCol = document.createElement('div');
        arCol.className = 'col-md-5';
        const inputAr = document.createElement('input');
        inputAr.type = 'text';
        inputAr.className = 'form-control';
        inputAr.name = `items_ar[${itemId}]`;
        inputAr.value = valueAr;
        inputAr.placeholder = 'العنصر بالعربية';
        arCol.appendChild(inputAr);

        const enCol = document.createElement('div');
        enCol.className = 'col-md-5';
        const inputEn = document.createElement('input');
        inputEn.type = 'text';
        inputEn.className = 'form-control';
        inputEn.name = `items_en[${itemId}]`;
        inputEn.value = valueEn;
        inputEn.placeholder = 'Item in English';
        enCol.appendChild(inputEn);

        const btnCol = document.createElement('div');
        btnCol.className = 'col-md-2 d-grid';
        
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'btn btn-outline-danger';
        deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
        deleteBtn.onclick = function() { removeItem(itemId); };
        btnCol.appendChild(deleteBtn);
        
        itemGroup.appendChild(arCol);
        itemGroup.appendChild(enCol);
        itemGroup.appendChild(btnCol);
        container.appendChild(itemGroup);
    }

    function removeItem(itemId) {
        const item = document.querySelector(`.item-group[data-item-id="${itemId}"]`);
        if (item) {
            item.remove();
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
        toggleContentType();
        
        // تحميل العناصر المحفوظة مسبقاً (في حالة التعديل أو إعادة المحاولة)
        @if(old('items_ar') || old('items_en'))
            @php
                $oldItemsAr = old('items_ar', []);
                $oldItemsEn = old('items_en', []);
                $maxItems = max(count($oldItemsAr), count($oldItemsEn));
            @endphp
            @for($i = 0; $i < $maxItems; $i++)
                addItem(
                    {!! json_encode($oldItemsAr[$i] ?? '') !!},
                    {!! json_encode($oldItemsEn[$i] ?? '') !!}
                );
            @endfor
        @else
            addItem();
        @endif

        // تحميل الأقسام المخصصة المحفوظة مسبقاً (في حالة التعديل أو إعادة المحاولة)
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