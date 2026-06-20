# 🎨 دليل الـ Views - واجهات المستخدم

## 📋 نظرة عامة

تم إنشاء **10 ملفات Blade Views** جديدة للميزات المضافة.

---

## 👤 واجهات المستخدمين (User Views)

### 1. نظام الحجوزات (3 ملفات)

#### 📄 `resources/views/website/bookings/index.blade.php`
**الغرض:** عرض قائمة حجوزات المستخدم

**الميزات:**
- ✅ عرض جميع الحجوزات
- ✅ تصفية حسب الحالة
- ✅ عرض رقم الحجز والتفاصيل
- ✅ زر إلغاء للحجوزات المعلقة
- ✅ Pagination

**الوصول:**
```
Route: /bookings
Method: GET
```

---

#### 📄 `resources/views/website/bookings/create.blade.php`
**الغرض:** نموذج إنشاء حجز جديد

**الميزات:**
- ✅ معلومات النشاط
- ✅ اختيار التاريخ وعدد الأشخاص
- ✅ إدخال كود الخصم
- ✅ التحقق الفوري من الكوبون (AJAX)
- ✅ حساب تلقائي للسعر
- ✅ عرض الخصم
- ✅ طلبات خاصة

**الوصول:**
```
Route: /activities/{activity}/book
Method: GET
```

**JavaScript المضمن:**
- حساب الإجمالي تلقائياً
- التحقق من الكوبون عبر AJAX
- تحديث السعر بعد الخصم

---

#### 📄 `resources/views/website/bookings/show.blade.php`
**الغرض:** عرض تفاصيل حجز معين

**الميزات:**
- ✅ رقم الحجز وحالته
- ✅ معلومات النشاط والوجهة
- ✅ تفاصيل الحجز الكاملة
- ✅ ملخص السعر
- ✅ زر الطباعة
- ✅ زر الإلغاء (للحجوزات المعلقة)

**الوصول:**
```
Route: /bookings/{booking}
Method: GET
```

---

### 2. جداول الرحلات (1 ملف)

#### 📄 `resources/views/website/itineraries/index.blade.php`
**الغرض:** عرض جميع جداول الرحلات

**الميزات:**
- ✅ عرض الجداول العامة والخاصة
- ✅ معلومات المؤلف
- ✅ مدة الرحلة
- ✅ عدد العناصر
- ✅ الميزانية المقدرة
- ✅ تمييز بين عامة/خاصة
- ✅ Pagination

**الوصول:**
```
Route: /itineraries
Method: GET
```

---

### 3. النقاط والولاء (1 ملف)

#### 📄 `resources/views/website/loyalty/index.blade.php`
**الغرض:** عرض نقاط المستخدم ومستواه

**الميزات:**
- ✅ بطاقة المستوى الحالي بتصميم مميز
- ✅ عرض النقاط الحالية
- ✅ Progress bar للمستوى التالي
- ✅ إحصائيات (مكتسب/مستخدم/شارات)
- ✅ مزايا المستوى
- ✅ عرض جميع الشارات
- ✅ سجل النقاط التفصيلي
- ✅ ألوان متدرجة حسب المستوى

**الوصول:**
```
Route: /loyalty
Method: GET
```

**CSS المضمن:**
- تدرج ألوان حسب المستوى
- أيقونات emoji كبيرة
- تصميم responsive

---

### 4. الإشعارات (1 ملف)

#### 📄 `resources/views/website/notifications/index.blade.php`
**الغرض:** عرض جميع الإشعارات

**الميزات:**
- ✅ تمييز الإشعارات الجديدة
- ✅ أيقونات حسب نوع الإشعار
- ✅ عرض تفاصيل كل إشعار
- ✅ زر تحديد كمقروء
- ✅ زر حذف
- ✅ زر تحديد الكل كمقروء
- ✅ عرض الوقت بشكل نسبي
- ✅ Pagination

**الوصول:**
```
Route: /notifications
Method: GET
```

**أنواع الإشعارات:**
- 📅 تأكيد حجز
- 🏅 شارة جديدة
- ⭐ نقاط جديدة

---

## 🛡️ واجهات الإدارة (Admin Views)

### 1. إدارة الحجوزات (1 ملف)

#### 📄 `resources/views/admin/bookings/index.blade.php`
**الغرض:** إدارة جميع الحجوزات

**الميزات:**
- ✅ جدول شامل للحجوزات
- ✅ فلترة حسب الحالة
- ✅ فلترة حسب التاريخ
- ✅ عرض معلومات المستخدم
- ✅ زر تأكيد سريع
- ✅ زر عرض التفاصيل
- ✅ زر الحذف
- ✅ Pagination

**الوصول:**
```
Route: /admin/bookings
Method: GET
```

---

### 2. إدارة الكوبونات (1 ملف)

#### 📄 `resources/views/admin/coupons/index.blade.php`
**الغرض:** إدارة الكوبونات

**الميزات:**
- ✅ جدول شامل للكوبونات
- ✅ عرض الكود بتنسيق مميز
- ✅ نوع الخصم (نسبة/ثابت)
- ✅ إحصائيات الاستخدام
- ✅ فترة الصلاحية
- ✅ زر تفعيل/تعطيل سريع
- ✅ زر التعديل
- ✅ زر الحذف
- ✅ Pagination

**الوصول:**
```
Route: /admin/coupons
Method: GET
```

---

### 3. إدارة الشارات (1 ملف)

#### 📄 `resources/views/admin/badges/index.blade.php`
**الغرض:** إدارة الشارات

**الميزات:**
- ✅ عرض بطاقات (Cards) للشارات
- ✅ أيقونة الشارة كبيرة
- ✅ الوصف الكامل
- ✅ النقاط المطلوبة
- ✅ عرض اللون
- ✅ عدد المستخدمين الحاصلين عليها
- ✅ زر التعديل
- ✅ زر الحذف
- ✅ Pagination

**الوصول:**
```
Route: /admin/badges
Method: GET
```

---

### 4. رسائل التواصل (1 ملف)

#### 📄 `resources/views/admin/contacts/index.blade.php`
**الغرض:** إدارة رسائل التواصل

**الميزات:**
- ✅ جدول شامل للرسائل
- ✅ تمييز الرسائل الجديدة
- ✅ فلترة حسب الحالة
- ✅ عرض معلومات المرسل
- ✅ الموضوع
- ✅ الحالة (جديد/مقروء/تم الرد)
- ✅ الوقت النسبي
- ✅ زر العرض
- ✅ زر الحذف
- ✅ Pagination

**الوصول:**
```
Route: /admin/contacts
Method: GET
```

---

## 🎨 التصميم والأسلوب

### استخدام Bootstrap 4/5
جميع الـ Views تستخدم Bootstrap مع:
- ✅ Cards
- ✅ Badges
- ✅ Tables
- ✅ Forms
- ✅ Buttons
- ✅ Alerts
- ✅ Progress bars

### الأيقونات
استخدام Font Awesome:
```html
<i class="fas fa-icon-name"></i>
```

### الألوان
```
- Primary: #007bff (أزرق)
- Success: #28a745 (أخضر)
- Warning: #ffc107 (أصفر)
- Danger: #dc3545 (أحمر)
- Info: #17a2b8 (سماوي)
- Secondary: #6c757d (رمادي)
```

---

## 📱 Responsive Design

جميع الـ Views متجاوبة مع جميع الأحجام:
- 📱 Mobile (< 576px)
- 📱 Tablet (576px - 768px)
- 💻 Desktop (768px - 992px)
- 🖥️ Large Desktop (> 992px)

---

## 🔄 تكامل مع الـ Layout

### للمستخدمين:
```blade
@extends('website.layouts.app')

@section('title', 'عنوان الصفحة')

@section('content')
    <!-- المحتوى -->
@endsection
```

### للإدارة:
```blade
@extends('admin.layouts.app')

@section('title', 'عنوان الصفحة')

@section('content')
    <!-- المحتوى -->
@endsection
```

---

## ⚡ JavaScript المضمن

### في `bookings/create.blade.php`:
```javascript
@push('scripts')
<script>
// حساب الإجمالي
// التحقق من الكوبون عبر AJAX
</script>
@endpush
```

### في `loyalty/index.blade.php`:
```javascript
@push('scripts')
<script>
// تفعيل tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>
@endpush
```

---

## 📊 ملخص الـ Views

### إجمالي الملفات: **10 ملفات**

#### حسب النوع:
- **User Views:** 6 ملفات
  - Bookings: 3
  - Itineraries: 1
  - Loyalty: 1
  - Notifications: 1

- **Admin Views:** 4 ملفات
  - Bookings: 1
  - Coupons: 1
  - Badges: 1
  - Contacts: 1

---

## 🎯 ملفات إضافية مطلوبة

لتكتمل الواجهات، ستحتاج إلى:

### Layouts:
```
resources/views/website/layouts/app.blade.php
resources/views/admin/layouts/app.blade.php
```

### Views إضافية (اختيارية):
```
resources/views/website/itineraries/create.blade.php
resources/views/website/itineraries/show.blade.php
resources/views/website/itineraries/edit.blade.php
resources/views/admin/coupons/create.blade.php
resources/views/admin/coupons/edit.blade.php
resources/views/admin/badges/create.blade.php
resources/views/admin/badges/edit.blade.php
resources/views/admin/bookings/show.blade.php
resources/views/admin/contacts/show.blade.php
resources/views/website/contact.blade.php
```

---

## 🔧 التخصيص

### تغيير الألوان:
ابحث عن `badge-primary` واستبدلها بـ:
- `badge-success`
- `badge-warning`
- `badge-danger`
- `badge-info`

### تغيير الأيقونات:
ابحث عن `fas fa-` واستبدل اسم الأيقونة من:
https://fontawesome.com/icons

---

## ✅ الخلاصة

تم إنشاء **10 ملفات View احترافية** تغطي:
- ✅ نظام الحجز الكامل
- ✅ جداول الرحلات
- ✅ النقاط والولاء
- ✅ الإشعارات
- ✅ إدارة الحجوزات
- ✅ إدارة الكوبونات
- ✅ إدارة الشارات
- ✅ رسائل التواصل

**المشروع الآن جاهز مع واجهات مستخدم احترافية! 🎨✨**




