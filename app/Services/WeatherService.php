<?php

namespace App\Services;

use App\Models\Weather;
use App\Models\Destination;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.weather.api_key');
        
        if (empty($this->apiKey)) {
            \Log::warning('Weather API key is not set. Please add OPENWEATHER_API_KEY or WEATHER_API_KEY to your .env file');
        }
    }

    /**
     * الحصول على الطقس الحالي لوجهة
     */
    public function getCurrentWeather(Destination $destination)
    {
        $locale = app()->getLocale() === 'en' ? 'en' : 'ar';

        // التحقق من وجود الإحداثيات
        if (!$destination->latitude || !$destination->longitude) {
            Log::warning('Destination missing coordinates', [
                'destination_id' => $destination->id,
                'name' => $destination->name,
            ]);
            return null;
        }

        // التحقق من وجود API Key
        if (empty($this->apiKey)) {
            Log::warning('Weather API key is missing', ['destination_id' => $destination->id]);
            return null;
        }

        // محاولة الحصول من قاعدة البيانات أولاً
        $existingWeather = Weather::getCurrentForDestination($destination->id);
        if ($existingWeather && $existingWeather->last_updated && 
            Carbon::parse($existingWeather->last_updated)->diffInMinutes(now()) < 30) {
            return $existingWeather;
        }

        // محاولة الحصول من الـ Cache
        $cacheKey = "weather_current_{$destination->id}_{$locale}";
        $cached = Cache::get($cacheKey);
        
        if ($cached && isset($cached['last_updated']) && 
            Carbon::parse($cached['last_updated'])->diffInMinutes(now()) < 30) {
            return $existingWeather ?? null;
        }

        try {
            $response = Http::get($this->baseUrl . '/weather', [
                'lat' => $destination->latitude,
                'lon' => $destination->longitude,
                'appid' => $this->apiKey,
                'units' => 'metric', // سيليسيوس
                'lang' => $locale,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // التحقق من صحة البيانات
                if (empty($data) || !isset($data['main'])) {
                    Log::warning('Weather API returned invalid data', [
                        'destination_id' => $destination->id,
                        'data' => $data,
                    ]);
                    return $existingWeather ?? null;
                }
                
                $weatherData = $this->transformWeatherData($destination->id, $data);
                
                // حفظ في قاعدة البيانات
                $weather = Weather::updateOrCreate(
                    [
                        'destination_id' => $destination->id,
                        'date' => Carbon::today(),
                    ],
                    $weatherData
                );

                // حفظ في الـ Cache لمدة 30 دقيقة
                Cache::put($cacheKey, $weather->toArray(), now()->addMinutes(30));

                return $weather;
            }

            Log::warning('Weather API failed', [
                'destination_id' => $destination->id,
                'status' => $response->status(),
                'response' => $response->body(),
                'url' => $this->baseUrl . '/weather',
                'params' => [
                    'lat' => $destination->latitude,
                    'lon' => $destination->longitude,
                    'appid' => substr($this->apiKey, 0, 10) . '...', // إخفاء جزء من المفتاح
                ],
            ]);

            // إرجاع البيانات الموجودة إذا كانت متوفرة
            return $existingWeather ?? null;
        } catch (\Exception $e) {
            Log::error('Weather API error', [
                'destination_id' => $destination->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // إرجاع البيانات الموجودة حتى لو كانت قديمة
            return $existingWeather ?? null;
        }
    }

    /**
     * الحصول على التوقعات لـ 7 أيام
     */
    public function getForecast(Destination $destination, $days = 7)
    {
        $locale = app()->getLocale() === 'en' ? 'en' : 'ar';

        if (!$destination->latitude || !$destination->longitude) {
            return [];
        }

        $cacheKey = "weather_forecast_{$destination->id}_{$locale}";
        $cached = Cache::get($cacheKey);
        
        if ($cached && Carbon::parse($cached['last_updated'])->diffInHours(now()) < 6) {
            return $cached['forecast'];
        }

        try {
            $response = Http::get($this->baseUrl . '/forecast', [
                'lat' => $destination->latitude,
                'lon' => $destination->longitude,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => $locale,
                'cnt' => $days * 8, // 8 فترات في اليوم
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $forecast = $this->transformForecastData($data);
                
                // حفظ في قاعدة البيانات
                $weather = Weather::firstOrNew([
                    'destination_id' => $destination->id,
                    'date' => Carbon::today(),
                ]);

                $weather->forecast = $forecast;
                $weather->last_updated = now();
                $weather->save();

                // حفظ في الـ Cache
                Cache::put($cacheKey, [
                    'forecast' => $forecast,
                    'last_updated' => now()->toIso8601String(),
                ], now()->addHours(6));

                return $forecast;
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Weather Forecast API error', [
                'destination_id' => $destination->id,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * الحصول على تحذيرات الطقس
     * ملاحظة: هذه الميزة تتطلب خطة مدفوعة في OpenWeatherMap One Call API 3.0
     * يمكنك استخدام API مجاني آخر أو تخطي هذه الميزة
     */
    public function getAlerts(Destination $destination)
    {
        if (!$destination->latitude || !$destination->longitude) {
            return [];
        }

        // ملاحظة: One Call API 3.0 يتطلب خطة مدفوعة
        // يمكنك استخدام API آخر للتحذيرات أو إرجاع array فارغ
        // إذا كان لديك API key مدفوع، قم بإلغاء التعليق عن الكود أدناه
        
        /*
        try {
            $response = Http::get('https://api.openweathermap.org/data/3.0/onecall', [
                'lat' => $destination->latitude,
                'lon' => $destination->longitude,
                'appid' => $this->apiKey,
                'exclude' => 'current,minutely,hourly,daily',
                'lang' => 'ar',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['alerts'] ?? [];
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Weather Alerts API error', [
                'destination_id' => $destination->id,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
        */

        // إرجاع array فارغ كقيمة افتراضية
        return [];
    }

    /**
     * تحديث بيانات الطقس لوجهة
     */
    public function updateWeatherForDestination(Destination $destination)
    {
        $weather = $this->getCurrentWeather($destination);
        $forecast = $this->getForecast($destination);
        $alerts = $this->getAlerts($destination);

        if ($weather) {
            $weather->forecast = $forecast;
            $weather->alerts = $alerts;
            $weather->last_updated = now();
            $weather->save();
        }

        return $weather;
    }

    /**
     * تحويل بيانات API إلى صيغة قاعدة البيانات
     */
    protected function transformWeatherData($destinationId, $apiData)
    {
        $main = $apiData['main'] ?? [];
        $weather = $apiData['weather'][0] ?? [];
        $wind = $apiData['wind'] ?? [];
        $clouds = $apiData['clouds'] ?? [];

        return [
            'destination_id' => $destinationId,
            'temperature' => $main['temp'] ?? null,
            'feels_like' => $main['feels_like'] ?? null,
            'humidity' => $main['humidity'] ?? null,
            'pressure' => $main['pressure'] ?? null,
            'wind_speed' => $wind['speed'] ?? null,
            'wind_degree' => $wind['deg'] ?? null,
            'visibility' => $apiData['visibility'] ?? null,
            'clouds' => $clouds['all'] ?? null,
            'condition' => isset($weather['main']) ? strtolower($weather['main']) : null,
            'description' => $weather['description'] ?? null,
            'icon' => $weather['icon'] ?? null,
            'date' => Carbon::today(),
            'time' => now(),
            'last_updated' => now(),
        ];
    }

    /**
     * تحويل بيانات التوقعات
     */
    protected function transformForecastData($apiData)
    {
        $forecast = [];
        $list = $apiData['list'] ?? [];

        foreach ($list as $item) {
            $main = $item['main'] ?? [];
            $weather = $item['weather'][0] ?? [];
            $wind = $item['wind'] ?? [];

            $forecast[] = [
                'datetime' => Carbon::parse($item['dt'])->toIso8601String(),
                'date' => Carbon::parse($item['dt'])->format('Y-m-d'),
                'time' => Carbon::parse($item['dt'])->format('H:i'),
                'temperature' => $main['temp'] ?? null,
                'feels_like' => $main['feels_like'] ?? null,
                'humidity' => $main['humidity'] ?? null,
                'pressure' => $main['pressure'] ?? null,
                'wind_speed' => $wind['speed'] ?? null,
                'wind_degree' => $wind['deg'] ?? null,
                'condition' => isset($weather['main']) ? strtolower($weather['main']) : null,
                'description' => $weather['description'] ?? null,
                'icon' => $weather['icon'] ?? null,
                'clouds' => ($item['clouds']['all'] ?? null),
            ];
        }

        return $forecast;
    }

    /**
     * الحصول على معلومات الطقس الكاملة (حالي + توقعات + تحذيرات)
     */
    public function getFullWeatherInfo(Destination $destination)
    {
        // التحقق من وجود API Key
        if (empty($this->apiKey)) {
            Log::warning('Weather API key is missing in getFullWeatherInfo', [
                'destination_id' => $destination->id,
            ]);
            // محاولة إرجاع البيانات الموجودة من قاعدة البيانات
            return Weather::getCurrentForDestination($destination->id);
        }
        
        $current = $this->getCurrentWeather($destination);
        
        if (!$current) {
            // محاولة إرجاع البيانات الموجودة حتى لو كانت قديمة
            return Weather::getCurrentForDestination($destination->id);
        }

        // إذا كانت التوقعات غير موجودة أو قديمة، احصل عليها
        if (empty($current->forecast) || 
            (!$current->last_updated || Carbon::parse($current->last_updated)->diffInHours(now()) > 6)) {
            try {
                $forecast = $this->getForecast($destination);
                if (!empty($forecast)) {
                    $current->forecast = $forecast;
                    $current->save();
                }
            } catch (\Exception $e) {
                Log::error('Failed to get forecast', [
                    'destination_id' => $destination->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // إذا كانت التحذيرات غير موجودة أو قديمة، احصل عليها
        // ملاحظة: التحذيرات تتطلب API مدفوع
        if (empty($current->alerts) || 
            (!$current->last_updated || Carbon::parse($current->last_updated)->diffInHours(now()) > 6)) {
            try {
                $alerts = $this->getAlerts($destination);
                $current->alerts = $alerts;
                $current->save();
            } catch (\Exception $e) {
                Log::error('Failed to get alerts', [
                    'destination_id' => $destination->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $current;
    }
}

