<?php

namespace App\Filament\Widgets;

use App\Models\Prequalification;
use Filament\Widgets\DoughnutChartWidget;

class TradesBreakdown extends DoughnutChartWidget
{
    protected ?string $heading = 'Prequal Trades';

    protected ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $rows = Prequalification::query()
            ->selectRaw('COALESCE(NULLIF(TRIM(trade), ""), "Other") as label, COUNT(*) as c')
            ->groupBy('label')
            ->orderByDesc('c')
            ->limit(6)
            ->pluck('c', 'label');

        $labels = $rows->keys()->all();
        $data = $rows->values()->all();

        return [
            'datasets' => [[
                'label' => 'Prequals',
                'data' => $data,
                'backgroundColor' => [
                    'rgba(16,185,129,.8)',
                    'rgba(59,130,246,.8)',
                    'rgba(99,102,241,.8)',
                    'rgba(234,179,8,.8)',
                    'rgba(244,63,94,.8)',
                    'rgba(107,114,128,.8)',
                ],
                'borderWidth' => 0,
            ]],
            'labels' => $labels,
        ];
    }
}

