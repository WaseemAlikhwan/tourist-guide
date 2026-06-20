<?php

namespace App\Console\Commands;

use App\Models\ActivityAssociation;
use App\Services\ActivityAssociationService;
use Illuminate\Console\Command;

class BuildActivityAssociations extends Command
{
    protected $signature = 'recommendations:build-associations {--from-date=} {--top=10 : Number of top event associations to display}';
    protected $description = 'Build activity association metrics from paid bookings and print top event links';

    public function handle(ActivityAssociationService $service): int
    {
        $fromDate = $this->option('from-date');
        $count = $service->rebuild($fromDate);

        $this->info("Association rows generated: {$count}");
        $top = (int) $this->option('top');

        if ($count > 0 && $top > 0) {
            $rows = ActivityAssociation::query()
                ->with(['activity', 'associatedActivity'])
                ->whereHas('activity', fn ($query) => $query->where('is_event', true))
                ->whereHas('associatedActivity', fn ($query) => $query->where('is_event', true))
                ->orderByDesc('confidence')
                ->orderByDesc('lift')
                ->limit($top)
                ->get();

            if ($rows->isNotEmpty()) {
                $this->newLine();
                $this->info('Top event associations:');
                $this->table(
                    ['Event', 'Associated Event', 'Support', 'Confidence', 'Lift', 'Co-occurrence'],
                    $rows->map(function (ActivityAssociation $row) {
                        return [
                            $row->activity?->name_en ?? $row->activity?->name_ar ?? '-',
                            $row->associatedActivity?->name_en ?? $row->associatedActivity?->name_ar ?? '-',
                            (string) $row->support,
                            (string) $row->confidence,
                            (string) $row->lift,
                            $row->co_occurrence_count,
                        ];
                    })->all()
                );
            } else {
                $this->warn('No event-only associations found yet. Try seeding more paid confirmed/completed bookings.');
            }
        }

        return self::SUCCESS;
    }
}
