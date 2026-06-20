# ⚡ دليل البدء السريع

## 🚀 تشغيل المشروع في 5 دقائق

### الخطوة 1: تثبيت المكتبات
```bash
composer install
npm install
```

### الخطوة 2: إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### الخطوة 3: إعداد قاعدة البيانات
في ملف `.env`:
```env
DB_DATABASE=tourist_guide
DB_USERNAME=root
DB_PASSWORD=
```

### الخطوة 4: إنشاء قاعدة البيانات
```sql
CREATE DATABASE tourist_guide;
```

### الخطوة 5: تشغيل الترحيلات والبيانات
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### الخطوة 6: التشغيل
```bash
npm run dev
# في terminal آخر:
php artisan serve
```

---

## 🎯 الوصول للنظام

### الموقع الرئيسي
👉 http://127.0.0.1:8000

### لوحة التحكم
👉 http://127.0.0.1:8000/admin/login
- **البريد:** admin@tourist.com
- **كلمة المرور:** password

---

## 📱 اختبار الميزات الجديدة

### 1. نظام الحجز
1. سجل دخول كمستخدم
2. اذهب إلى الأنشطة
3. اختر نشاط واضغط "احجز الآن"
4. أدخل البيانات واستخدم كوبون `WELCOME2024`
5. ستحصل على نقاط تلقائياً!

### 2. جدول الرحلات
1. اذهب إلى `/itineraries/create`
2. أنشئ جدول رحلة جديد
3. أضف أنشطة ووجهات
4. شارك الجدول أو اجعله خاص

### 3. النقاط والشارات
1. اذهب إلى `/loyalty`
2. شاهد نقاطك ومستواك
3. تابع الشارات المكتسبة
4. اطلع على سجل النقاط

### 4. الإشعارات
1. اذهب إلى `/notifications`
2. شاهد جميع إشعاراتك
3. حدّد الإشعارات كمقروءة

---

## 🎟️ كوبونات جاهزة للاختبار

استخدم هذه الكوبونات:
- `WELCOME2024` - خصم 20%
- `SUMMER50` - خصم 50 وحدة
- `VIP15` - خصم 15%
- `NEWUSER100` - خصم 100 وحدة
- `EARLYBIRD` - خصم 25%

---

## 🔧 أوامر مفيدة

```bash
# مسح الكاش
php artisan cache:clear
php artisan config:clear

# إعادة البناء
php artisan migrate:fresh --seed

# تشغيل Queue
php artisan queue:work
```

---

## 📊 لوحة التحكم الإدارية

### الأقسام المتاحة:
- 📍 **الوجهات** - `/admin/destinations`
- 🎯 **الأنشطة** - `/admin/activities`
- 📅 **الحجوزات** - `/admin/bookings`
- 🎟️ **الكوبونات** - `/admin/coupons`
- 🏆 **الشارات** - `/admin/badges`
- 📸 **المعرض** - إضافة من صفحة الوجهة/النشاط
- 📧 **الرسائل** - `/admin/contacts`

---

## 🎨 تخصيص سريع

### تغيير نقاط المستويات:
في `app/Models/User.php` - دالة `updateTier()`

### تعديل الشارات:
في `database/seeders/BadgeSeeder.php`

### إضافة كوبونات:
في لوحة التحكم `/admin/coupons/create`

---

## 🐛 حل مشكلة شائعة

### الصور لا تظهر؟
```bash
php artisan storage:link
```

### أخطاء في الصلاحيات (Linux/Mac)?
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📞 دعم سريع

**مشكلة في التثبيت؟**
تحقق من:
1. ✅ PHP >= 8.1
2. ✅ Composer مثبت
3. ✅ MySQL يعمل
4. ✅ ملف .env صحيح

---

**جاهز للاستكشاف! 🌍✨**




