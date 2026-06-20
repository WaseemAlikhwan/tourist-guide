<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * الحصول على معلومات الطقس لوجهة
     */
    public function getWeather(Destination $destination)
    {
        $weather = $this->weatherService->getFullWeatherInfo($destination);

        if (!$weather) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن الحصول على معلومات الطقس لهذه الوجهة',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'current' => $this->formatCurrentWeather($weather),
                'forecast' => $this->formatForecast($weather->forecast ?? []),
                'alerts' => $this->formatAlerts($weather->alerts ?? []),
            ],
        ]);
    }

    /**
     * تحديث بيانات الطقس لوجهة
     */
    public function updateWeather(Destination $destination)
    {
        try {
            $weather = $this->weatherService->updateWeatherForDestination($destination);

            if ($weather) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث بيانات الطقس بنجاح',
                    'data' => $this->formatCurrentWeather($weather),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تحديث بيانات الطقس',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث بيانات الطقس',
            ], 500);
        }
    }

    /**
     * تنسيق بيانات الطقس الحالي
     */
    protected function formatCurrentWeather($weather)
    {
        return [
            'temperature' => $weather->temperature ? round($weather->temperature, 1) : null,
            'feels_like' => $weather->feels_like ? round($weather->feels_like, 1) : null,
            'humidity' => $weather->humidity,
            'wind_speed' => $weather->wind_speed ? round($weather->wind_speed, 1) : null,
            'wind_direction' => $weather->wind_direction,
            'pressure' => $weather->pressure,
            'visibility' => $weather->visibility ? round($weather->visibility / 1000, 1) : null, // تحويل لكم
            'clouds' => $weather->clouds,
            'condition' => $weather->condition,
            'condition_arabic' => $weather->condition_arabic,
            'description' => $weather->description,
            'icon' => $weather->icon,
            'icon_url' => $weather->icon ? "https://openweathermap.org/img/wn/{$weather->icon}@2x.png" : null,
            'last_updated' => $weather->last_updated ? $weather->last_updated->format('Y-m-d H:i:s') : null,
        ];
    }

    /**
     * تنسيق التوقعات
     */
    protected function formatForecast($forecast)
    {
        if (empty($forecast)) {
            return [];
        }

        $grouped = collect($forecast)->groupBy('date')->map(function ($items, $date) {
            $dayData = $items->first();
            $temps = $items->pluck('temperature')->filter();
            
            return [
                'date' => $date,
                'date_formatted' => \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('l d F'),
                'temperature_min' => $temps->min(),
                'temperature_max' => $temps->max(),
                'temperature_avg' => round($temps->avg(), 1),
                'condition' => $dayData['condition'] ?? null,
                'description' => $dayData['description'] ?? null,
                'icon' => $dayData['icon'] ?? null,
                'icon_url' => isset($dayData['icon']) ? "https://openweathermap.org/img/wn/{$dayData['icon']}@2x.png" : null,
                'humidity' => round(collect($items)->pluck('humidity')->avg()),
                'wind_speed' => round(collect($items)->pluck('wind_speed')->avg(), 1),
            ];
        })->values()->take(7);

        return $grouped->toArray();
    }

    /**
     * تنسيق التحذيرات
     */
    protected function formatAlerts($alerts)
    {
        if (empty($alerts)) {
            return [];
        }

        return collect($alerts)->map(function ($alert) {
            return [
                'sender_name' => $alert['sender_name'] ?? null,
                'event' => $alert['event'] ?? null,
                'start' => isset($alert['start']) ? \Carbon\Carbon::createFromTimestamp($alert['start'])->format('Y-m-d H:i:s') : null,
                'end' => isset($alert['end']) ? \Carbon\Carbon::createFromTimestamp($alert['end'])->format('Y-m-d H:i:s') : null,
                'description' => $alert['description'] ?? null,
                'tags' => $alert['tags'] ?? [],
            ];
        })->toArray();
    }
}
