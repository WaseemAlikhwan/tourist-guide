@extends('admin.layouts.app')

@section('title', 'إضافة فندق جديد | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.hotels.index') }}" class="text-decoration-none">الفنادق</a></span>
        <span>›</span>
        <span>إضافة فندق جديد</span>
    </div>

    <div class="page-header">
        <div>
            <h1>إضافة فندق جديد 🏨</h1>
            <p class="subtitle">أضف فندقاً قريباً من إحدى الوجهات السياحية</p>
        </div>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> العودة للقائمة
        </a>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="name_ar" class="form-label fw-semibold">اسم الفندق (AR) <span class="text-danger">*</span></label>
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
                            <label for="name_en" class="form-label fw-semibold">Hotel Name (EN)</label>
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
                                        {{ $destination->name }} - {{ $destination->country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <label for="address_ar" class="form-label fw-semibold">العنوان (AR)</label>
                            <input type="text" 
                                   class="form-control @error('address_ar') is-invalid @enderror" 
                                   id="address_ar" 
                                   name="address_ar" 
                                   value="{{ old('address_ar') }}">
                            @error('address_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address_en" class="form-label fw-semibold">Address (EN)</label>
                            <input type="text" 
                                   class="form-control @error('address_en') is-invalid @enderror" 
                                   id="address_en" 
                                   name="address_en" 
                                   value="{{ old('address_en') }}">
                            @error('address_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="phone" class="form-label fw-semibold">رقم الهاتف</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="website" class="form-label fw-semibold">الموقع الإلكتروني</label>
                            <input type="url" 
                                   class="form-control @error('website') is-invalid @enderror" 
                                   id="website" 
                                   name="website" 
                                   value="{{ old('website') }}"
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="star_rating" class="form-label fw-semibold">التصنيف (النجوم) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('star_rating') is-invalid @enderror"
                                            id="star_rating"
                                            name="star_rating"
                                            required>
                                        <option value="">اختر التصنيف</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('star_rating', 3) == $i ? 'selected' : '' }}>
                                                {{ str_repeat('⭐', $i) }} ({{ $i }} نجوم)
                                            </option>
                                        @endfor
                                    </select>
                                    @error('star_rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="price_per_night" class="form-label fw-semibold">السعر لليلة الواحدة</label>
                                    <input type="number" 
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('price_per_night') is-invalid @enderror" 
                                           id="price_per_night" 
                                           name="price_per_night" 
                                           value="{{ old('price_per_night') }}">
                                    @error('price_per_night')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <div class="form-check mt-4 pt-3">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_active">
                                            نشط
                                        </label>
                                    </div>
                                </div>
                            </div>
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
                                           value="{{ old('latitude') }}" 
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
                                           value="{{ old('longitude') }}" 
                                           placeholder="مثال: 46.6753">
                                    <small class="text-muted">اختياري - للإشارات الجغرافية</small>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- المرافق والخدمات -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">المرافق والخدمات</label>
                            <div class="row">
                                @php
                                    $commonAmenities = [
                                        'واي فاي مجاني', 'موقف سيارات', 'مسبح', 'صالة ألعاب رياضية', 
                                        'مطعم', 'بار', 'غرفة إفطار', 'خدمة الغرف', 'سبا', 
                                        'مكيف هواء', 'تلفزيون', 'ميني بار', 'خدمة الغسيل',
                                        'مصعد', 'خدمة الاستقبال 24/7', 'خدمة النقل من/إلى المطار'
                                    ];
                                @endphp
                                @foreach($commonAmenities as $amenity)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="amenity_{{ $loop->index }}" 
                                                   name="amenities_ar[]" 
                                                   value="{{ $amenity }}"
                                                   {{ in_array($amenity, old('amenities_ar', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="amenity_{{ $loop->index }}">
                                                {{ $amenity }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">يمكنك إضافة مرافق أخرى في حقل الوصف</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">صورة الفندق</label>
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
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> إضافة الفندق
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
</script>
@endpush
