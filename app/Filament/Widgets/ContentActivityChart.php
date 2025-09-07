<?php

namespace App\Filament\Widgets;

use App\Models\{Post, Project, ContactMessage};
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class ContentActivityChart extends LineChartWidget
{
    protected ?string $heading = 'Activity (12 months)';

    protected ?string $maxHeight = '280px';

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $start = now()->startOfMonth()->subMonths(11);

        $labels = [];
        $keys = [];
        $cursor = $start->copy();
        for ($i = 0; $i < 12; $i++) {
            $labels[] = $cursor->format('M');
            $keys[] = $cursor->format('Y-m');
            $cursor->addMonth();
        }

        $seedMap = fn () => array_fill_keys($keys, 0);
        $accumulate = function ($dates) use ($seedMap) {
            $map = $seedMap();
            foreach ($dates as $dt) {
                $key = \Illuminate\Support\Carbon::parse($dt)->format('Y-m');
                if (isset($map[$key])) $map[$key]++;
            }
            return array_values($map);
        };

        $postsData = $accumulate(
            Post::query()->where('created_at', '>=', $start)->pluck('created_at')
        );
        $projectsData = $accumulate(
            Project::query()->where('created_at', '>=', $start)->pluck('created_at')
        );
        $messagesData = $accumulate(
            ContactMessage::query()->where('created_at', '>=', $start)->pluck('created_at')
        );

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => $postsData,
                    'tension' => 0.35,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, .25)',
                    'fill' => true,
                ],
                [
                    'label' => 'Projects',
                    'data' => $projectsData,
                    'tension' => 0.35,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, .25)',
                    'fill' => true,
                ],
                [
                    'label' => 'Messages',
                    'data' => $messagesData,
                    'tension' => 0.35,
                    'borderColor' => 'rgb(244, 63, 94)',
                    'backgroundColor' => 'rgba(244, 63, 94, .2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
