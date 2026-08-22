<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class TopPages extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        // Grouped in a subquery: Filament adds its own ORDER BY id, which
        // MySQL's ONLY_FULL_GROUP_BY rejects on a grouped outer query.
        $perPath = PageView::query()
            ->select(DB::raw('MIN(id) as id'), 'path', DB::raw('count(*) as total'), DB::raw('count(distinct session_id) as visitors'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('path');

        return $table
            ->heading('Halaman Terpopuler (30 Hari)')
            ->query(PageView::query()->fromSub($perPath, 'page_views'))
            ->defaultSort('total', 'desc')
            ->paginated([5, 10, 25])
            ->columns([
                TextColumn::make('path')->label('Halaman')->url(fn ($record) => url($record->path))->openUrlInNewTab(),
                TextColumn::make('visitors')->label('Pengunjung')->sortable(),
                TextColumn::make('total')->label('Dilihat')->sortable(),
            ]);
    }
}
