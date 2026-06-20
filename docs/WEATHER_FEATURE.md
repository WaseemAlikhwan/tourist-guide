# 🌤️ نظام معلومات الطقس والتوقعات

تم إضافة نظام شامل لعرض معلومات الطقس والتوقعات والتحذيرات للوجهات السياحية.

---

## 📋 المميزات

- ✅ **الطقس الحالي**: درجة الحرارة، الرطوبة، الرياح، الضغط الجوي، الرؤية
- ✅ **التوقعات**: توقعات الطقس لـ 7 أيام قادمة
- ✅ **التحذيرات**: تنبيهات الطقس القاسي (إن وجدت)
- ✅ **تحديث تلقائي**: تحديث البيانات كل ساعتين
- ✅ **واجهة جميلة**: تصميم حديث وعرض واضح

---

## 🚀 التثبيت والإعداد

### 1. تشغيل Migration

```bash
php artisan migrate
```

### 2. الحصول على API Key من OpenWeatherMap

1. سجل في موقع [OpenWeatherMap](https://openweathermap.org/api)
2. احصل على API Key مجاني (Free tier يتيح 60 طلب/دقيقة)
3. أضف المفتاح إلى ملف `.env`:

```env
WEATHER_API_KEY=your_api_key_here
```

### 3. إعداد الوجهات

تأكد أن الوجهات لديها إحداثيات جغرافية (latitude و longitude):

```php
// في قاعدة البيانات أو من خلال Admin Panel
$destination->latitude = 24.7136;
$destination->longitude = 46.6753;
$destination->save();
```

---

## 📁 الملفات المضافة

### Models
- `app/Models/Weather.php` - نموذج بيانات الطقس

### Services
- `app/Services/WeatherService.php` - خدمة التعامل مع Weather API

### Controllers
- `app/Http/Controllers/WeatherController.php` - Controller للطقس

### Jobs
- `app/Jobs/UpdateWeatherJob.php` - Job لتحديث الطقس تلقائياً

### Migrations
- `database/migrations/xxxx_create_weather_table.php` - جدول الطقس

### Views
- تم تحديث `resources/views/website/destinations/show.blade.php` لإضافة قسم الطقس

### Routes
- `GET /destinations/{destination}/weather` - الحصول على بيانات الطقس
- `POST /destinations/{destination}/weather/update` - تحديث بيانات الطقس

---

## 🔧 الاستخدام

### في الكود

```php
use App\Services\WeatherService;
use App\Models\Destination;

$weatherService = app(WeatherService::class);
$destination = Destination::find(1);

// الحصول على معلومات الطقس الكاملة
$weather = $weatherService->getFullWeatherInfo($destination);

// تحديث بيانات الطقس
$weather = $weatherService->updateWeatherForDestination($destination);
```

### في View

يتم عرض معلومات الطقس تلقائياً في صفحة تفاصيل الوجهة (`/destinations/{destination}`) إذا كانت الوجهة لديها إحداثيات.

---

## ⚙️ التحديث التلقائي

تم إعداد Job لتحديث بيانات الطقس تلقائياً كل ساعتين. للتأكد من عملها:

1. تأكد من تفعيل Queue (أو استخدم `sync` للتطوير)

2. أضف Cron Job في السيرفر:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

أو استخدم Laravel Scheduler إذا كان متاحاً.

---

## 📊 هيكل قاعدة البيانات

### جدول `weather`

| العمود | النوع | الوصف |
|--------|------|-------|
| id | bigint | المعرف |
| destination_id | bigint | معرف الوجهة |
| temperature | decimal(5,2) | درجة الحرارة |
| feels_like | decimal(5,2) | الشعور بدرجة الحرارة |
| humidity | integer | الرطوبة % |
| wind_speed | decimal(5,2) | سرعة الرياح m/s |
| wind_degree | integer | اتجاه الرياح |
| pressure | integer | الضغط الجوي hPa |
| visibility | integer | الرؤية بالأمتار |
| clouds | integer | الغيوم % |
| condition | string | حالة الطقس |
| description | string | الوصف |
| icon | string | رمز الأيقونة |
| date | date | التاريخ |
| forecast | json | التوقعات لـ 7 أيام |
| alerts | json | التحذيرات |
| last_updated | timestamp | آخر تحديث |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

---

## 🎨 الواجهة

يعرض النظام:

1. **بطاقة الطقس الحالي**: تصميم جميل مع gradient يظهر:
   - درجة الحرارة الكبيرة
   - الوصف والشعور بدرجة الحرارة
   - الرطوبة، الرياح، الضغط، الرؤية
   - أيقونة الطقس

2. **التحذيرات**: في حالة وجود تحذيرات طقس قاسي

3. **التوقعات لـ 7 أيام**: بطاقات منفصلة لكل يوم تظهر:
   - اليوم والتاريخ
   - أيقونة الطقس
   - درجة الحرارة العليا والدنيا
   - الوصف

---

## 🔐 الأمان

- يتم تخزين API Key في `.env` وليس في الكود
- يتم استخدام Cache لتقليل عدد طلبات API
- معالجة الأخطاء بشكل مناسب

---

## ⚠️ ملاحظات مهمة

1. **حدود API**: 
   - Free tier: 60 طلب/دقيقة
   - تأكد من عدم تجاوز الحدود

2. **التكلفة**: 
   - الخطة المجانية كافية للتطوير والاستخدام المحدود
   - للاستخدام الكبير، قد تحتاج خطة مدفوعة

3. **الأداء**:
   - يتم استخدام Cache لمدة 30 دقيقة للطقس الحالي
   - يتم استخدام Cache لمدة 6 ساعات للتوقعات

4. **الإحداثيات**:
   - يجب أن يكون للوجهة `latitude` و `longitude` لعرض الطقس

---

## 🐛 حل المشاكل

### لا يظهر الطقس

1. تأكد من وجود API Key في `.env`
2. تأكد من أن الوجهة لديها إحداثيات
3. تحقق من logs: `storage/logs/laravel.log`

### خطأ في API

```php
// تحقق من الـ API Key
php artisan tinker
>>> config('services.weather.api_key')
```

### تحديث البيانات

يمكنك تحديث بيانات الطقس يدوياً:

```php
// في Tinker
$destination = Destination::find(1);
$weatherService = app(\App\Services\WeatherService::class);
$weatherService->updateWeatherForDestination($destination);
```

أو من الواجهة: اضغط على زر "تحديث" في قسم الطقس.

---

## 📚 المراجع

- [OpenWeatherMap API Documentation](https://openweathermap.org/api)
- [Laravel Queue Documentation](https://laravel.com/docs/queues)
- [Laravel Scheduling](https://laravel.com/docs/scheduling)

---

## 🎉 تم بنجاح!

نظام الطقس جاهز للاستخدام! استمتع بمعلومات الطقس الدقيقة لوجهاتك السياحية! 🌤️✨







