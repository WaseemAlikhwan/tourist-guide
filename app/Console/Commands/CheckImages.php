<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Destination;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckImages extends Command
{
    protected $signature = 'images:check';
    protected $description = 'Check and report missing images';

    public function handle()
    {
        $this->info('Checking images...');
        $this->newLine();

        // Check Destinations
        $this->info('=== DESTINATIONS ===');
        $destinations = Destination::whereNotNull('image')->get();
        $missingDests = 0;
        
        foreach ($destinations as $dest) {
            $fullPath = storage_path('app/public/' . $dest->image);
            $exists = file_exists($fullPath);
            
            if (!$exists) {
                $missingDests++;
                $this->warn("  ✗ ID {$dest->id} ({$dest->name}): {$dest->image}");
                $this->line("    Path: {$fullPath}");
            } else {
                $this->info("  ✓ ID {$dest->id} ({$dest->name}): {$dest->image}");
            }
        }
        
        if ($destinations->isEmpty()) {
            $this->line('  No destinations with images found.');
        }

        $this->newLine();

        // Check Activities
        $this->info('=== ACTIVITIES ===');
        $activities = Activity::whereNotNull('image')->get();
        $missingActs = 0;
        
        foreach ($activities as $act) {
            $fullPath = storage_path('app/public/' . $act->image);
            $exists = file_exists($fullPath);
            
            if (!$exists) {
                $missingActs++;
                $this->warn("  ✗ ID {$act->id} ({$act->name}): {$act->image}");
                $this->line("    Path: {$fullPath}");
            } else {
                $this->info("  ✓ ID {$act->id} ({$act->name}): {$act->image}");
            }
        }
        
        if ($activities->isEmpty()) {
            $this->line('  No activities with images found.');
        }

        $this->newLine();
        $this->info("=== SUMMARY ===");
        $this->line("Missing destination images: {$missingDests}");
        $this->line("Missing activity images: {$missingActs}");
        
        if ($missingDests > 0 || $missingActs > 0) {
            $this->newLine();
            $this->warn("⚠️  Some images are missing!");
            $this->line("Make sure you:");
            $this->line("1. Run: php artisan storage:link");
            $this->line("2. Upload images again or copy them from the old project folder");
        } else {
            $this->info("✓ All images are present!");
        }

        return 0;
    }
}
