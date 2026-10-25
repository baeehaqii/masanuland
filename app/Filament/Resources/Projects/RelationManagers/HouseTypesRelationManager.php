<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Support\SvgIcon;
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
use Filament\Support\RawJs;
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
            TextInput::make('price')->label('Harga')->placeholder('450.000.000')
                ->prefix('Rp')->numeric()
                ->mask(RawJs::make("\$money(\$input, ',', '.', 0)"))
                ->stripCharacters('.'),
            FileUpload::make('image')->label('Gambar Utama')->helperText('Foto atau denah utama yang tampil di kartu tipe.')->image()->directory('house-types'),
            TextInput::make('brochure_url')->label('Link Brosur')->placeholder('https://drive.google.com/file/d/xxxxxxxx')->url(),
            TextInput::make('sort')->label('Urutan')->placeholder('1')->numeric()->default(0),
            Repeater::make('floor_plans')
                ->label('Denah Lainnya')
                ->helperText('Mis. Denah Lantai 1, Denah Lantai 2. Dibuka ukuran penuh saat diklik di website.')
                ->schema([
                    FileUpload::make('image')->label('Gambar Denah')->image()->directory('house-types')->required(),
                    TextInput::make('label')->label('Keterangan')->required()->placeholder('Denah Lantai 1'),
                ])
                ->columns(2)
                ->grid(2)
                ->reorderable()
                ->defaultItems(0)
                ->columnSpanFull(),
            Repeater::make('specs')
                ->label('Spesifikasi')
                ->helperText('Tampil sebagai ikon + jumlah. Tanpa ikon SVG, dipakai ikon bawaan sesuai nama ruang.')
                ->schema([
                    SvgIcon::upload(),
                    TextInput::make('count')->label('Jumlah')->placeholder('2')->required()->default(1),
                    TextInput::make('label')->label('Ruang')->required()->placeholder('Kamar Tidur'),
                ])
                ->columns(3)
                ->reorderable()
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
                TextColumn::make('price')->label('Harga')->sortable()
                    ->formatStateUsing(fn (?int $state): ?string => $state ? 'Rp '.number_format($state, 0, ',', '.') : null),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
