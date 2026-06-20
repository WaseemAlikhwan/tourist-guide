<?php

namespace App\Jobs;

use App\Models\Destination;
use App\Services\WeatherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateWeatherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $destinationId;

    /**
     * Create a new job instance.
     */
    public function __construct($destinationId = null)
    {
        $this->destinationId = $destinationId;
    }

    /**
     * Execute the job.
     */
    public function handle(WeatherService $weatherService): void
    {
        try {
            if ($this->destinationId) {
                // تحديث طقس وجهة محددة
                $destination = Destination::find($this->destinationId);
                if ($destination && $destination->latitude && $destination->longitude) {
                    $weatherService->updateWeatherForDestination($destination);
                }
            } else {
                // تحديث طقس جميع الوجهات التي لديها إحداثيات
                $destinations = Destination::whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->get();

                foreach ($destinations as $destination) {
                    try {
                        $weatherService->updateWeatherForDestination($destination);
                        // تأخير بسيط لتجنب تجاوز حدود API
                        usleep(200000); // 0.2 ثانية
                    } catch (\Exception $e) {
                        Log::error('Failed to update weather for destination', [
                            'destination_id' => $destination->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('UpdateWeatherJob failed', [
                'destination_id' => $this->destinationId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * عدد مرات إعادة المحاولة
     */
    public $tries = 3;

    /**
     * الوقت قبل إعادة المحاولة
     */
    public $backoff = [60, 120, 300]; // 1 دقيقة، 2 دقيقة، 5 دقائق
}
