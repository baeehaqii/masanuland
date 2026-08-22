<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PlatformChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Perangkat Pengunjung (30 Hari)';

    protected ?string $maxHeight = '260px';

    protected function getType(): string
    {
        return 'doughnut';
    }

    /** @return array<string, mixed> */
    protected function getData(): array
    {
        $rows = PageView::where('created_at', '>=', now()->subDays(30))
            ->select('platform', DB::raw('count(*) as total'))
            ->groupBy('platform')->pluck('total', 'platform');

        return [
            'datasets' => [[
                'label' => 'Kunjungan',
                'data' => [(int) ($rows['mobile'] ?? 0), (int) ($rows['desktop'] ?? 0)],
                'backgroundColor' => ['#7a0f1b', '#c9962c'],
            ]],
            'labels' => ['Mobile', 'Desktop'],
        ];
    }
}
