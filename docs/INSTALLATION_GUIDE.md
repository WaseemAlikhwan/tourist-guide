# 📖 دليل التثبيت والتشغيل - نظام الدليل السياحي

## 🚀 خطوات التثبيت السريعة

### 1. المتطلبات الأساسية
تأكد من توفر هذه المتطلبات على جهازك:
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM (للأصول الأمامية)
- Git

### 2. تثبيت المشروع

```bash
# استنساخ المشروع (إذا كان من Git)
git clone <repository-url>
cd tourist-guide

# تثبيت مكتبات PHP
composer install

# تثبيت مكتبات JavaScript
npm install
```

### 3. إعداد ملف البيئة

```bash
# نسخ ملف البيئة
cp .env.example .env

# توليد مفتاح التطبيق
php artisan key:generate
```

### 4. إعداد قاعدة البيانات

افتح ملف `.env` وعدّل إعدادات قاعدة البيانات:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tourist_guide
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. إنشاء قاعدة البيانات

```sql
-- في MySQL/MariaDB
CREATE DATABASE tourist_guide CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. تشغيل الترحيلات (Migrations)

```bash
# تشغيل جميع الترحيلات
php artisan migrate

# أو إعادة تشغيل الترحيلات (يحذف البيانات القديمة!)
php artisan migrate:fresh
```

### 7. تشغيل البيانات الأولية (Seeders)

```bash
# تشغيل جميع Seeders
php artisan db:seed

# أو تشغيل Seeders محددة
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=BadgeSeeder
php artisan db:seed --class=CouponSeeder
```

### 8. إنشاء رابط التخزين

```bash
php artisan storage:link
```

### 9. بناء الأصول الأمامية

```bash
# للتطوير
npm run dev

# للإنتاج
npm run build
```

### 10. تشغيل السيرفر

```bash
php artisan serve
```

الآن يمكنك الوصول للتطبيق على: `http://127.0.0.1:8000`

---

## 🔑 بيانات تسجيل الدخول الافتراضية

### لوحة التحكم الإدارية:
- **الرابط:** `http://127.0.0.1:8000/admin/login`
- **البريد الإلكتروني:** `admin@tourist.com`
- **كلمة المرور:** `password`

---

## 📊 هيكل قاعدة البيانات

### الجداول الرئيسية:

#### 1. جدول المستخدمين (users)
```sql
- id
- name
- email
- password
- role (user/admin)
- loyalty_points (النقاط)
- tier (المستوى: bronze/silver/gold/platinum)
- timestamps
```

#### 2. جدول الوجهات (destinations)
```sql
- id
- name
- country
- description
- image
- latitude
- longitude
- timestamps
```

#### 3. جدول الأنشطة (activities)
```sql
- id
- destination_id
- name
- type
- price
- rating
- location
- description
- image
- timestamps
```

#### 4. جدول الحجوزات (bookings)
```sql
- id
- user_id
- activity_id
- booking_date
- number_of_people
- total_price
- status (pending/confirmed/cancelled/completed)
- special_requests
- booking_reference
- timestamps
```

#### 5. جدول جداول الرحلات (itineraries)
```sql
- id
- user_id
- title
- description
- start_date
- end_date
- estimated_budget
- is_public
- timestamps
```

#### 6. جدول عناصر الرحلات (itinerary_items)
```sql
- id
- itinerary_id
- activity_id
- destination_id
- date
- start_time
- end_time
- notes
- order
- timestamps
```

#### 7. جدول النقاط (loyalty_points)
```sql
- id
- user_id
- points
- type (earned/redeemed/expired)
- reason
- description
- timestamps
```

#### 8. جدول الشارات (badges)
```sql
- id
- name
- slug
- description
- icon
- color
- points_required
- timestamps
```

#### 9. جدول الكوبونات (coupons)
```sql
- id
- code
- description
- discount_type (percentage/fixed)
- discount_value
- minimum_purchase
- usage_limit
- usage_count
- valid_from
- valid_until
- is_active
- timestamps
```

#### 10. جدول معرض الصور (galleries)
```sql
- id
- galleryable_type
- galleryable_id
- image_path
- caption
- order
- is_featured
- timestamps
```

---

## 🛠️ الأوامر المفيدة

### تنظيف الكاش
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### إعادة تحميل Autoload
```bash
composer dump-autoload
```

### تشغيل Queue (للإشعارات)
```bash
php artisan queue:work
```

### إنشاء Controller جديد
```bash
php artisan make:controller ControllerName
```

### إنشاء Model جديد
```bash
php artisan make:model ModelName -m
```

### إنشاء Migration جديد
```bash
php artisan make:migration create_table_name
```

---

## 🔧 حل المشاكل الشائعة

### المشكلة: خطأ في الاتصال بقاعدة البيانات
**الحل:**
```bash
# تأكد من إعدادات قاعدة البيانات في .env
# تأكد من تشغيل MySQL
# حاول إعادة تشغيل الخادم
```

### المشكلة: خطأ في الصلاحيات
**الحل:**
```bash
# على Linux/Mac
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache

# على Windows (في PowerShell كمسؤول)
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

### المشكلة: الصور لا تظهر
**الحل:**
```bash
# تأكد من إنشاء رابط التخزين
php artisan storage:link

# تأكد من صلاحيات مجلد storage
```

### المشكلة: خطأ 404 على جميع الصفحات
**الحل:**
```bash
# حاول مسح الكاش
php artisan route:clear
php artisan config:clear

# تأكد من ملف .htaccess في مجلد public
```

---

## 📁 هيكل المجلدات الرئيسية

```
tourist-guide/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controllers الإدارة
│   │   │   └── User/           # Controllers المستخدمين
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/                 # نماذج البيانات
│   ├── Notifications/          # الإشعارات
│   ├── Policies/               # صلاحيات الوصول
│   └── Services/               # خدمات الأعمال
├── database/
│   ├── migrations/             # ترحيلات قاعدة البيانات
│   └── seeders/                # البيانات الأولية
├── resources/
│   └── views/
│       ├── admin/              # واجهات الإدارة
│       └── website/            # واجهات المستخدمين
├── routes/
│   └── web.php                 # مسارات التطبيق
└── public/
    └── storage/                # الملفات المرفوعة
```

---

## 🌐 إعداد البيئة الإنتاجية

### 1. تحديث ملف .env

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. تحسين الأداء

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
npm run build
```

### 3. إعداد SSL

استخدم Let's Encrypt أو شهادة SSL مدفوعة:
```bash
# مثال باستخدام Certbot
sudo certbot --nginx -d yourdomain.com
```

### 4. إعداد Cron Jobs

أضف إلى crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📧 إعداد البريد الإلكتروني

في ملف `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🔐 نصائح الأمان

1. **لا تشارك ملف .env أبداً**
2. **استخدم كلمات مرور قوية**
3. **فعّل HTTPS في الإنتاج**
4. **حدّث المكتبات بانتظام:**
   ```bash
   composer update
   npm update
   ```
5. **فعّل Rate Limiting على الـ Routes الحساسة**
6. **استخدم Queue للعمليات الثقيلة**

---

## 📞 الدعم

للمساعدة والاستفسارات:
- 📧 Email: support@touristguide.com
- 🌐 Website: https://touristguide.com
- 📚 Documentation: https://docs.touristguide.com

---

**تم التطوير بواسطة Laravel 10 ❤️**




