<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VisitorsChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Kunjungan Harian (30 Hari)';

    protected ?string $maxHeight = '260px';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    /** @return array<string, mixed> */
    protected function getData(): array
    {
        $rows = PageView::where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->select(DB::raw('date(created_at) as day'), DB::raw('count(*) as total'))
            ->groupBy('day')->pluck('total', 'day');

        $days = collect(range(29, 0))->map(fn (int $back) => now()->subDays($back));

        return [
            'datasets' => [[
                'label' => 'Halaman dilihat',
                'data' => $days->map(fn ($day) => (int) ($rows[$day->toDateString()] ?? 0))->all(),
                'borderColor' => '#7a0f1b',
                'backgroundColor' => 'rgba(122, 15, 27, 0.12)',
                'fill' => true,
                'tension' => 0.35,
            ]],
            'labels' => $days->map(fn ($day) => $day->translatedFormat('d M'))->all(),
        ];
    }
}
