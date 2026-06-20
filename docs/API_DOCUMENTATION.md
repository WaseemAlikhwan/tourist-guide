# 🔌 توثيق API - نظام الدليل السياحي

## 📋 نظرة عامة

يوفر النظام مجموعة شاملة من نقاط النهاية (Endpoints) لإدارة الوجهات، الأنشطة، الحجوزات، والميزات الأخرى.

---

## 🔐 المصادقة (Authentication)

معظم نقاط النهاية تتطلب مصادقة. يستخدم النظام Laravel Sanctum للمصادقة.

### تسجيل الدخول
```http
POST /login
Content-Type: application/x-www-form-urlencoded

email=user@example.com
password=password
```

### تسجيل حساب جديد
```http
POST /register
Content-Type: application/x-www-form-urlencoded

name=John Doe
email=user@example.com
password=password
password_confirmation=password
```

### تسجيل الخروج
```http
POST /logout
Authorization: Bearer {token}
```

---

## 🌍 الوجهات (Destinations)

### 1. قائمة الوجهات
```http
GET /destinations
```

**الاستجابة:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "القاهرة",
      "country": "مصر",
      "description": "عاصمة مصر التاريخية",
      "image_url": "http://example.com/storage/destinations/cairo.jpg",
      "latitude": 30.0444,
      "longitude": 31.2357
    }
  ]
}
```

### 2. تفاصيل وجهة
```http
GET /destinations/{id}
```

**الاستجابة:**
```json
{
  "id": 1,
  "name": "القاهرة",
  "country": "مصر",
  "description": "عاصمة مصر التاريخية",
  "image_url": "http://example.com/storage/destinations/cairo.jpg",
  "latitude": 30.0444,
  "longitude": 31.2357,
  "activities": [...],
  "gallery": [...]
}
```

---

## 🎯 الأنشطة (Activities)

### 1. قائمة الأنشطة
```http
GET /activities
```

**معاملات الاستعلام (Query Parameters):**
- `destination_id` - تصفية حسب الوجهة
- `type` - تصفية حسب النوع
- `min_price` - الحد الأدنى للسعر
- `max_price` - الحد الأقصى للسعر

**مثال:**
```http
GET /activities?destination_id=1&min_price=100&max_price=500
```

### 2. تفاصيل نشاط
```http
GET /activities/{id}
```

**الاستجابة:**
```json
{
  "id": 1,
  "name": "جولة الأهرامات",
  "type": "تاريخي",
  "price": 250.00,
  "rating": 4.8,
  "location": "الجيزة",
  "description": "جولة مذهلة لاستكشاف الأهرامات",
  "image_url": "...",
  "destination": {...},
  "reviews": [...],
  "gallery": [...]
}
```

---

## 📅 الحجوزات (Bookings)

### 1. قائمة حجوزاتي
```http
GET /bookings
Authorization: Bearer {token}
```

### 2. إنشاء حجز جديد
```http
POST /activities/{activity_id}/book
Authorization: Bearer {token}
Content-Type: application/json

{
  "booking_date": "2024-01-15",
  "number_of_people": 3,
  "special_requests": "نريد مرشد يتحدث الإنجليزية",
  "coupon_code": "SUMMER50"
}
```

**الاستجابة:**
```json
{
  "message": "تم إنشاء الحجز بنجاح!",
  "booking": {
    "id": 123,
    "booking_reference": "BK-ABC123XYZ",
    "status": "pending",
    "total_price": 200.00,
    "booking_date": "2024-01-15",
    "number_of_people": 3
  }
}
```

### 3. تفاصيل حجز
```http
GET /bookings/{id}
Authorization: Bearer {token}
```

### 4. إلغاء حجز
```http
POST /bookings/{id}/cancel
Authorization: Bearer {token}
```

### 5. التحقق من صحة كوبون
```http
POST /bookings/validate-coupon
Authorization: Bearer {token}
Content-Type: application/json

{
  "coupon_code": "SUMMER50",
  "total_price": 500
}
```

**الاستجابة:**
```json
{
  "valid": true,
  "discount": 50.00,
  "new_total": 450.00,
  "message": "تم تطبيق الكوبون بنجاح"
}
```

---

## 🗓️ جداول الرحلات (Itineraries)

### 1. قائمة جداول الرحلات
```http
GET /itineraries
```

### 2. إنشاء جدول رحلة
```http
POST /itineraries
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "رحلة مصر 2024",
  "description": "رحلة عائلية لاستكشاف مصر",
  "start_date": "2024-02-01",
  "end_date": "2024-02-10",
  "estimated_budget": 5000,
  "is_public": true
}
```

### 3. تفاصيل جدول رحلة
```http
GET /itineraries/{id}
```

### 4. تحديث جدول رحلة
```http
PUT /itineraries/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "رحلة مصر المحدثة",
  "is_public": false
}
```

### 5. حذف جدول رحلة
```http
DELETE /itineraries/{id}
Authorization: Bearer {token}
```

### 6. إضافة عنصر لجدول الرحلة
```http
POST /itineraries/{id}/items
Authorization: Bearer {token}
Content-Type: application/json

{
  "activity_id": 5,
  "date": "2024-02-02",
  "start_time": "09:00",
  "end_time": "12:00",
  "notes": "احجز التذاكر مسبقاً"
}
```

### 7. حذف عنصر من جدول الرحلة
```http
DELETE /itineraries/{itinerary_id}/items/{item_id}
Authorization: Bearer {token}
```

---

## 🏆 النقاط والولاء (Loyalty Points)

### 1. عرض نقاطي ومستواي
```http
GET /loyalty
Authorization: Bearer {token}
```

**الاستجابة:**
```json
{
  "user": {
    "name": "أحمد محمد",
    "loyalty_points": 2500,
    "tier": "silver"
  },
  "earned_points": 3000,
  "redeemed_points": 500,
  "tier_info": {
    "name": "فضي",
    "min_points": 2000,
    "max_points": 4999,
    "color": "#C0C0C0",
    "benefits": [...]
  },
  "next_tier": {
    "name": "ذهبي",
    "points_needed": 2500
  },
  "badges": [...]
}
```

---

## ⭐ التقييمات (Reviews)

### 1. إضافة تقييم
```http
POST /activities/{activity_id}/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
  "rating": 5,
  "comment": "تجربة رائعة!"
}
```

### 2. تحديث تقييم
```http
PATCH /reviews/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "rating": 4,
  "comment": "تجربة جيدة"
}
```

### 3. حذف تقييم
```http
DELETE /reviews/{id}
Authorization: Bearer {token}
```

---

## ❤️ المفضلة (Favorites)

### 1. قائمة المفضلة
```http
GET /favorites
Authorization: Bearer {token}
```

### 2. إضافة/إزالة من المفضلة
```http
POST /favorites/toggle
Authorization: Bearer {token}
Content-Type: application/json

{
  "favoritable_type": "App\\Models\\Activity",
  "favoritable_id": 5
}
```

**الاستجابة:**
```json
{
  "message": "تمت الإضافة للمفضلة",
  "is_favorited": true
}
```

---

## 🔔 الإشعارات (Notifications)

### 1. قائمة الإشعارات
```http
GET /notifications
Authorization: Bearer {token}
```

### 2. عدد الإشعارات غير المقروءة
```http
GET /notifications/unread-count
Authorization: Bearer {token}
```

**الاستجابة:**
```json
{
  "count": 5
}
```

### 3. تحديد إشعار كمقروء
```http
POST /notifications/{id}/read
Authorization: Bearer {token}
```

### 4. تحديد جميع الإشعارات كمقروءة
```http
POST /notifications/read-all
Authorization: Bearer {token}
```

### 5. حذف إشعار
```http
DELETE /notifications/{id}
Authorization: Bearer {token}
```

---

## 📞 التواصل (Contact)

### إرسال رسالة تواصل
```http
POST /contact
Content-Type: application/json

{
  "name": "أحمد محمد",
  "email": "ahmed@example.com",
  "phone": "0123456789",
  "subject": "استفسار عن الخدمات",
  "message": "أود الاستفسار عن..."
}
```

---

## 🔧 نقاط نهاية الإدارة (Admin Endpoints)

جميع نقاط النهاية الإدارية تتطلب تسجيل دخول كمسؤول.

### الحجوزات

#### 1. قائمة جميع الحجوزات
```http
GET /admin/bookings?status=pending&date_from=2024-01-01
Authorization: Bearer {admin_token}
```

#### 2. تحديث حالة الحجز
```http
POST /admin/bookings/{id}/status
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "status": "confirmed"
}
```

### الكوبونات

#### 1. قائمة الكوبونات
```http
GET /admin/coupons
Authorization: Bearer {admin_token}
```

#### 2. إنشاء كوبون جديد
```http
POST /admin/coupons
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "code": "NEWYEAR2024",
  "description": "خصم رأس السنة",
  "discount_type": "percentage",
  "discount_value": 25,
  "minimum_purchase": 200,
  "usage_limit": 100,
  "valid_from": "2024-01-01",
  "valid_until": "2024-01-31",
  "is_active": true
}
```

#### 3. تفعيل/تعطيل كوبون
```http
POST /admin/coupons/{id}/toggle-status
Authorization: Bearer {admin_token}
```

### الشارات

#### 1. قائمة الشارات
```http
GET /admin/badges
Authorization: Bearer {admin_token}
```

#### 2. إنشاء شارة جديدة
```http
POST /admin/badges
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "مستكشف الصحراء",
  "description": "زر 5 وجهات صحراوية",
  "icon": "🏜️",
  "color": "#F4A460",
  "points_required": 800
}
```

### معرض الصور

#### 1. إضافة صورة
```http
POST /admin/gallery
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

galleryable_type=App\Models\Destination
galleryable_id=1
image={file}
caption=منظر رائع
is_featured=true
```

#### 2. حذف صورة
```http
DELETE /admin/gallery/{id}
Authorization: Bearer {admin_token}
```

### رسائل التواصل

#### 1. قائمة الرسائل
```http
GET /admin/contacts?status=new
Authorization: Bearer {admin_token}
```

#### 2. تحديث حالة رسالة
```http
POST /admin/contacts/{id}/status
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "status": "replied"
}
```

---

## 📊 رموز الحالة (Status Codes)

| الكود | المعنى | الوصف |
|------|--------|-------|
| 200 | OK | نجحت العملية |
| 201 | Created | تم الإنشاء بنجاح |
| 400 | Bad Request | بيانات غير صحيحة |
| 401 | Unauthorized | غير مصرح |
| 403 | Forbidden | ممنوع الوصول |
| 404 | Not Found | غير موجود |
| 422 | Unprocessable Entity | خطأ في التحقق |
| 500 | Server Error | خطأ في الخادم |

---

## 🎯 أمثلة على الاستخدام

### مثال 1: حجز نشاط باستخدام cURL

```bash
curl -X POST http://localhost:8000/activities/1/book \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "booking_date": "2024-02-15",
    "number_of_people": 2,
    "coupon_code": "SUMMER50"
  }'
```

### مثال 2: الحصول على قائمة الوجهات باستخدام JavaScript

```javascript
fetch('http://localhost:8000/destinations')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));
```

### مثال 3: إنشاء جدول رحلة باستخدام Axios

```javascript
axios.post('http://localhost:8000/itineraries', {
  title: 'رحلة القاهرة',
  description: 'رحلة عائلية',
  start_date: '2024-03-01',
  end_date: '2024-03-07',
  estimated_budget: 3000,
  is_public: true
}, {
  headers: {
    'Authorization': `Bearer ${token}`
  }
})
.then(response => console.log(response.data))
.catch(error => console.error(error));
```

---

## 🔒 معدلات الحد (Rate Limiting)

النظام يطبق معدلات حد للحماية من الإساءة:

- **للضيوف:** 60 طلب في الدقيقة
- **للمستخدمين المسجلين:** 120 طلب في الدقيقة
- **للإدارة:** 300 طلب في الدقيقة

---

## 📝 ملاحظات مهمة

1. جميع التواريخ بصيغة `YYYY-MM-DD`
2. جميع الأوقات بصيغة `HH:MM`
3. الأسعار بالعملة المحلية
4. الصور يجب أن تكون بصيغة: jpg, png, gif
5. الحد الأقصى لحجم الصورة: 2MB

---

## 🐛 الإبلاغ عن الأخطاء

للإبلاغ عن أخطاء API:
- Email: api-support@touristguide.com
- GitHub Issues: https://github.com/tourist-guide/issues

---

**تم التوثيق بواسطة فريق التطوير ✨**




