# 🗄️ هيكل قاعدة البيانات - نظام الدليل السياحي

## 📊 نظرة عامة

النظام يحتوي على **15 جدول رئيسي** مع علاقات معقدة ومتداخلة.

---

## 📋 الجداول الأساسية

### 1. users (المستخدمون)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)
email               VARCHAR(255) UNIQUE
email_verified_at   TIMESTAMP NULL
password            VARCHAR(255)
role                ENUM('user', 'admin') DEFAULT 'user'
loyalty_points      INT DEFAULT 0
tier                VARCHAR(20) DEFAULT 'bronze'
remember_token      VARCHAR(100)
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `hasMany` → bookings
- `hasMany` → itineraries
- `hasMany` → favorites
- `hasMany` → reviews
- `hasMany` → loyalty_points
- `belongsToMany` → badges
- `belongsToMany` → coupons

---

### 2. destinations (الوجهات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)
country             VARCHAR(255)
description         TEXT
image               VARCHAR(255)
latitude            DECIMAL(10, 8) NULL
longitude           DECIMAL(11, 8) NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `hasMany` → activities
- `morphMany` → favorites
- `morphMany` → gallery
- `hasMany` → itinerary_items

---

### 3. activities (الأنشطة)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
destination_id      BIGINT UNSIGNED FOREIGN KEY
name                VARCHAR(255)
type                VARCHAR(100)
price               DECIMAL(10, 2)
rating              DECIMAL(3, 2) DEFAULT 0
location            VARCHAR(255)
description         TEXT
image               VARCHAR(255)
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsTo` → destination
- `hasMany` → reviews
- `hasMany` → bookings
- `morphMany` → favorites
- `morphMany` → gallery
- `hasMany` → itinerary_items

---

## 📅 جداول نظام الحجز

### 4. bookings (الحجوزات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
user_id             BIGINT UNSIGNED FOREIGN KEY
activity_id         BIGINT UNSIGNED FOREIGN KEY
booking_date        DATE
number_of_people    INT DEFAULT 1
total_price         DECIMAL(10, 2)
status              ENUM('pending', 'confirmed', 'cancelled', 'completed')
special_requests    TEXT NULL
booking_reference   VARCHAR(255) UNIQUE
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsTo` → user
- `belongsTo` → activity
- `hasMany` → coupon_user

---

## 🗓️ جداول جداول الرحلات

### 5. itineraries (جداول الرحلات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
user_id             BIGINT UNSIGNED FOREIGN KEY
title               VARCHAR(255)
description         TEXT NULL
start_date          DATE
end_date            DATE
estimated_budget    DECIMAL(10, 2) NULL
is_public           BOOLEAN DEFAULT FALSE
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsTo` → user
- `hasMany` → itinerary_items

### 6. itinerary_items (عناصر جداول الرحلات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
itinerary_id        BIGINT UNSIGNED FOREIGN KEY
activity_id         BIGINT UNSIGNED FOREIGN KEY NULL
destination_id      BIGINT UNSIGNED FOREIGN KEY NULL
date                DATE
start_time          TIME NULL
end_time            TIME NULL
notes               TEXT NULL
order               INT DEFAULT 0
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsTo` → itinerary
- `belongsTo` → activity
- `belongsTo` → destination

---

## 🏆 جداول النقاط والولاء

### 7. loyalty_points (سجل النقاط)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
user_id             BIGINT UNSIGNED FOREIGN KEY
points              INT
type                ENUM('earned', 'redeemed', 'expired')
reason              VARCHAR(255)
description         TEXT NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsTo` → user

### 8. badges (الشارات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)
slug                VARCHAR(255) UNIQUE
description         TEXT
icon                VARCHAR(255) NULL
color               VARCHAR(20) DEFAULT '#3490dc'
points_required     INT DEFAULT 0
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsToMany` → users

### 9. badge_user (جدول الربط: المستخدمين والشارات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
badge_id            BIGINT UNSIGNED FOREIGN KEY
user_id             BIGINT UNSIGNED FOREIGN KEY
earned_at           TIMESTAMP
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

---

## 🎟️ جداول الكوبونات

### 10. coupons (الكوبونات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
code                VARCHAR(255) UNIQUE
description         TEXT NULL
discount_type       ENUM('percentage', 'fixed')
discount_value      DECIMAL(8, 2)
minimum_purchase    DECIMAL(10, 2) NULL
usage_limit         INT NULL
usage_count         INT DEFAULT 0
valid_from          DATE
valid_until         DATE
is_active           BOOLEAN DEFAULT TRUE
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsToMany` → users

### 11. coupon_user (جدول الربط: استخدام الكوبونات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
coupon_id           BIGINT UNSIGNED FOREIGN KEY
user_id             BIGINT UNSIGNED FOREIGN KEY
booking_id          BIGINT UNSIGNED FOREIGN KEY NULL
used_at             TIMESTAMP
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsTo` → coupon
- `belongsTo` → user
- `belongsTo` → booking

---

## 📸 جدول معرض الصور

### 12. galleries (المعرض)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
galleryable_type    VARCHAR(255)
galleryable_id      BIGINT UNSIGNED
image_path          VARCHAR(255)
caption             VARCHAR(255) NULL
order               INT DEFAULT 0
is_featured         BOOLEAN DEFAULT FALSE
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات (Polymorphic):**
- `morphTo` → galleryable (Destination or Activity)

---

## ⭐ جداول التقييمات والمفضلة

### 13. reviews (التقييمات)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
user_id             BIGINT UNSIGNED FOREIGN KEY
activity_id         BIGINT UNSIGNED FOREIGN KEY
rating              INT
comment             TEXT
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات:**
- `belongsTo` → user
- `belongsTo` → activity

### 14. favorites (المفضلة)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
user_id             BIGINT UNSIGNED FOREIGN KEY
favoritable_type    VARCHAR(255)
favoritable_id      BIGINT UNSIGNED
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات (Polymorphic):**
- `belongsTo` → user
- `morphTo` → favoritable (Destination or Activity)

---

## 🔔 جدول الإشعارات

### 15. notifications (الإشعارات)
```sql
id                  UUID PRIMARY KEY
type                VARCHAR(255)
notifiable_type     VARCHAR(255)
notifiable_id       BIGINT UNSIGNED
data                TEXT
read_at             TIMESTAMP NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**العلاقات (Polymorphic):**
- `morphTo` → notifiable (عادةً User)

---

## 📧 جدول التواصل

### 16. contacts (رسائل التواصل)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)
email               VARCHAR(255)
phone               VARCHAR(20) NULL
subject             VARCHAR(255)
message             TEXT
status              ENUM('new', 'read', 'replied')
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

---

## 🔗 مخطط العلاقات (ERD)

```
users
├── bookings ──→ activities ──→ destinations
├── itineraries ──→ itinerary_items
│                  ├──→ activities
│                  └──→ destinations
├── loyalty_points
├── favorites (polymorphic)
│   ├──→ destinations
│   └──→ activities
├── reviews ──→ activities
├── badges (many-to-many)
└── coupons (many-to-many) ──→ bookings

destinations
├── activities
├── gallery (polymorphic)
├── favorites (polymorphic)
└── itinerary_items

activities
├── bookings
├── reviews
├── gallery (polymorphic)
├── favorites (polymorphic)
└── itinerary_items

bookings
├── coupon_user ──→ coupons
└── notifications
```

---

## 🔑 المفاتيح الأجنبية (Foreign Keys)

### قيود الحذف (ON DELETE):
- `CASCADE` - حذف السجلات المرتبطة تلقائياً
  - users → bookings
  - users → itineraries
  - activities → bookings
  
- `SET NULL` - تعيين NULL عند الحذف
  - activities → itinerary_items
  - destinations → itinerary_items
  - bookings → coupon_user

---

## 📊 الفهارس (Indexes)

### الفهارس الأساسية:
```sql
-- للبحث السريع
INDEX idx_activities_destination (destination_id)
INDEX idx_bookings_user (user_id)
INDEX idx_bookings_date (booking_date)
INDEX idx_bookings_status (status)

-- للعلاقات Polymorphic
INDEX idx_favorites_favoritable (favoritable_type, favoritable_id)
INDEX idx_galleries_galleryable (galleryable_type, galleryable_id)

-- للكوبونات
UNIQUE INDEX idx_coupons_code (code)
INDEX idx_coupons_valid (is_active, valid_from, valid_until)
```

---

## 🎯 أمثلة على الاستعلامات

### 1. حجوزات مستخدم مع الأنشطة والوجهات:
```php
$bookings = User::find(1)
    ->bookings()
    ->with('activity.destination')
    ->latest()
    ->get();
```

### 2. نقاط المستخدم مع الشارات:
```php
$userData = User::with([
    'loyaltyPointsHistory',
    'badges'
])->find(1);
```

### 3. جدول رحلة مع جميع العناصر:
```php
$itinerary = Itinerary::with([
    'items.activity',
    'items.destination'
])->find(1);
```

### 4. نشاط مع المعرض والتقييمات:
```php
$activity = Activity::with([
    'gallery',
    'reviews.user',
    'destination'
])->find(1);
```

### 5. كوبونات نشطة وصالحة:
```php
$coupons = Coupon::active()
    ->where('usage_count', '<', 'usage_limit')
    ->get();
```

---

## 🔐 قيود البيانات (Constraints)

### قيود التحقق:
- `bookings.number_of_people` - يجب أن يكون >= 1
- `activities.rating` - بين 0 و 5
- `loyalty_points.points` - يجب أن يكون > 0
- `coupons.discount_value` - يجب أن يكون > 0

### القيم الافتراضية:
- `users.role` = 'user'
- `users.loyalty_points` = 0
- `users.tier` = 'bronze'
- `bookings.status` = 'pending'
- `itineraries.is_public` = false
- `coupons.is_active` = true

---

## 📈 إحصائيات قاعدة البيانات

- **عدد الجداول:** 16
- **عدد العلاقات:** 25+
- **علاقات Polymorphic:** 3 (favorites, gallery, notifications)
- **جداول ربط:** 2 (badge_user, coupon_user)
- **المفاتيح الأجنبية:** 18+

---

## 🛠️ أوامر مفيدة

### عرض هيكل جدول:
```sql
DESCRIBE table_name;
```

### عرض جميع الجداول:
```sql
SHOW TABLES;
```

### عرض العلاقات:
```sql
SELECT * FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'tourist_guide';
```

---

**تصميم قاعدة بيانات متقدم ومحسّن! 🗄️✨**




