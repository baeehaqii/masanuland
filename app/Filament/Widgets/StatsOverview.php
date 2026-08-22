<?php

namespace App\Filament\Widgets;

use App\Models\HouseType;
use App\Models\PageView;
use App\Models\Project;
use App\Models\Testimonial;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|array|null $columns = 3;

    /** @return array<Stat> */
    protected function getStats(): array
    {
        $published = Project::where('is_published', true)->count();
        $views30 = PageView::where('created_at', '>=', now()->subDays(30));
        $visitors30 = (clone $views30)->distinct('session_id')->count('session_id');

        return [
            Stat::make('Total Perumahan', (string) Project::count())
                ->description($published.' tayang di website')
                ->descriptionIcon(Heroicon::OutlinedBuildingOffice2)
                ->color('success'),

            Stat::make('Tipe Rumah', (string) HouseType::count())
                ->description('Denah & tipe terdaftar')
                ->descriptionIcon(Heroicon::OutlinedHome)
                ->color('success'),

            Stat::make('Testimoni', (string) Testimonial::where('is_published', true)->count())
                ->description(Testimonial::count().' total testimoni')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('warning'),

            Stat::make('Pengunjung Hari Ini', (string) PageView::whereDate('created_at', today())
                ->distinct('session_id')->count('session_id'))
                ->description('Sesi unik hari ini')
                ->descriptionIcon(Heroicon::OutlinedBolt)
                ->color('warning'),

            Stat::make('Pengunjung (30 Hari)', (string) $visitors30)
                ->description('Sesi unik 30 hari terakhir')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('success'),

            Stat::make('Page Views (30 Hari)', (string) (clone $views30)->count())
                ->description('Total halaman dilihat')
                ->descriptionIcon(Heroicon::OutlinedEye)
                ->chart($this->last7Days())
                ->color('info'),
        ];
    }

    /** @return array<int> */
    private function last7Days(): array
    {
        $rows = PageView::where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->select(DB::raw('date(created_at) as day'), DB::raw('count(*) as total'))
            ->groupBy('day')->pluck('total', 'day');

        return collect(range(6, 0))
            ->map(fn (int $back) => (int) ($rows[now()->subDays($back)->toDateString()] ?? 0))
            ->all();
    }
}
