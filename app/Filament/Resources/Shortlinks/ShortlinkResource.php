<?php

namespace App\Filament\Resources\Shortlinks;

use App\Filament\Resources\Shortlinks\Pages\CreateShortlink;
use App\Filament\Resources\Shortlinks\Pages\EditShortlink;
use App\Filament\Resources\Shortlinks\Pages\ListShortlinks;
use App\Models\Shortlink;
use App\Support\UIcons;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class ShortlinkResource extends Resource
{
    protected static ?string $model = Shortlink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|UnitEnum|null $navigationGroup = 'Proyek';

    protected static ?string $modelLabel = 'Shortlink';

    protected static ?string $pluralModelLabel = 'Shortlink';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Profil')->schema([
                TextInput::make('title')->label('Judul')->required()->placeholder('MASANU RESIDENCE WIRADADI'),
                TextInput::make('slug')->label('Alamat Link')->required()->alphaDash()->unique(ignoreRecord: true)
                    ->prefix(url('/l').'/')->placeholder('masanuresidencewiradadi'),
                TextInput::make('handle')->label('Nama Akun')->placeholder('masanuresidencewiradadi')
                    ->helperText('Tampil kecil di bawah logo.'),
                TextInput::make('subtitle')->label('Keterangan')
                    ->placeholder('Jl. Protokol Desa Wiradadi, Dusun III, Kecamatan Sokaraja, Kabupaten Banyumas'),
                FileUpload::make('logo')->label('Logo')->image()->directory('shortlinks')
                    ->helperText('Persegi, tampil bulat.'),
                FileUpload::make('background')->label('Gambar Latar')->image()->directory('shortlinks')
                    ->helperText('Kosongkan untuk latar warna polos.'),
                ColorPicker::make('button_color')->label('Warna Tombol')->placeholder('#7a0f1b'),
                Toggle::make('is_published')->label('Aktif')->default(true),
            ])->columns(2)->columnSpanFull(),

            Section::make('Daftar Link')->schema([
                Repeater::make('links')
                    ->hiddenLabel()
                    ->schema([
                        Select::make('icon')->label('Ikon')
                            ->placeholder('Ketik untuk mencari, mis. map, instagram, whatsapp')
                            ->searchable()
                            ->searchDebounce(300)
                            ->allowHtml()
                            ->getSearchResultsUsing(fn (string $search) => UIcons::search($search))
                            ->getOptionLabelUsing(fn (?string $value) => UIcons::label($value))
                            // Filament meng-evaluate closure di rules(), jadi rule-nya dibungkus satu lapis.
                            ->rules([fn (): Closure => function (string $attribute, mixed $value, Closure $fail) {
                                if (filled($value) && ! in_array($value, UIcons::all(), true)) {
                                    $fail('Ikon tidak dikenal.');
                                }
                            }]),
                        TextInput::make('title')->label('Judul')->required()->placeholder('LOKASI PROJEK'),
                        TextInput::make('subtitle')->label('Keterangan')->placeholder('Google Maps'),
                        TextInput::make('url')->label('Link')->required()->url()
                            ->placeholder('https://maps.app.goo.gl/xxxxxxxx'),
                    ])
                    ->columns(2)
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->defaultItems(1)
                    ->addActionLabel('Tambah link'),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('slug')->label('Link')
                    ->formatStateUsing(fn (string $state) => url('/l/'.$state))
                    ->copyable()->copyableState(fn (string $state) => url('/l/'.$state)),
                TextColumn::make('links')->label('Jumlah Link')
                    ->state(fn (Shortlink $record) => count($record->links ?? [])),
                ToggleColumn::make('is_published')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Shortlink $record) => url('/l/'.$record->slug), shouldOpenInNewTab: true),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShortlinks::route('/'),
            'create' => CreateShortlink::route('/create'),
            'edit' => EditShortlink::route('/{record}/edit'),
        ];
    }
}
