<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HouseTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'houseTypes';

    protected static ?string $title = 'Tipe Rumah';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Tipe')->required()->placeholder('T-45/72'),
            TextInput::make('price_label')->label('Harga')->placeholder('Rp 450.000.000 ,-'),
            FileUpload::make('image')->label('Gambar')->image()->directory('house-types'),
            TextInput::make('brochure_url')->label('Link Brosur')->url(),
            TextInput::make('sort')->label('Urutan')->numeric()->default(0),
            Repeater::make('specs')
                ->label('Spesifikasi')
                ->schema([
                    TextInput::make('count')->label('Jumlah')->required()->default(1),
                    TextInput::make('label')->label('Ruang')->required()->placeholder('Kamar Tidur'),
                ])
                ->columns(2)
                ->defaultItems(0)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('image')->label('')->imageHeight(40),
                TextColumn::make('name')->label('Tipe')->searchable(),
                TextColumn::make('price_label')->label('Harga'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
