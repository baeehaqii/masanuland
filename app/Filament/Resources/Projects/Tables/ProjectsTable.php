<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('card_image')->label('')->imageHeight(40),
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('tagline')->label('Tagline')->limit(40),
                // money('IDR') memakai locale aplikasi dan mencetak "IDR 450,000,000.00".
                TextColumn::make('price_from')->label('Harga Mulai')->sortable()
                    ->formatStateUsing(fn (?int $state): ?string => $state ? 'Rp '.number_format($state, 0, ',', '.') : null),
                TextColumn::make('house_types_count')->counts('houseTypes')->label('Tipe'),
                ToggleColumn::make('is_published')->label('Tayang'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
