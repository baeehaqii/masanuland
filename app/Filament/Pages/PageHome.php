<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PageHome extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $title = 'Halaman Beranda';

    protected static ?string $navigationLabel = 'Beranda';

    protected static ?int $navigationSort = 1;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['hero_slides', 'hero_image', 'hero_title', 'hero_subtitle', 'hero_note', 'hero_badges', 'page_home'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make()->columnSpanFull()->tabs([
                    Tabs\Tab::make('Slider Header')->schema([
                        FileUpload::make('hero_slides')->label('Slider Header')
                            ->helperText('Banner di bawah menu. Rasio 1920x786 px. Kosongkan untuk memakai banner bawaan.')
                            ->image()->multiple()->reorderable()->appendFiles()
                            ->directory('site')->columnSpanFull(),
                        FileUpload::make('hero_image')->label('Gambar Hero (cadangan slider)')->image()->directory('site'),
                        TextInput::make('hero_title')->label('Judul Hero'),
                        TextInput::make('hero_subtitle')->label('Sub Judul Hero'),
                        TextInput::make('hero_note')->label('Catatan Kecil')->placeholder('*S&K Berlaku'),
                        TagsInput::make('hero_badges')->label('Badge Promo')
                            ->placeholder('Gratis Pajak Pembeli')->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Bagian Tentang')->schema([
                        TextInput::make('page_home.about_eyebrow')->label('Label Kecil')->placeholder('Tentang Kami'),
                        TextInput::make('page_home.about_title')->label('Judul'),
                        TextInput::make('page_home.about_link_label')->label('Teks Tombol')->placeholder('Selengkapnya'),
                        FileUpload::make('page_home.about_image')->label('Gambar / Foto Fasad')->image()->directory('site'),
                    ])->columns(2),

                    Tabs\Tab::make('Bagian Perumahan')->schema([
                        TextInput::make('page_home.projects_title')->label('Judul')->placeholder('Lokasi Unggulan'),
                        TextInput::make('page_home.projects_subtitle')->label('Sub Judul'),
                    ])->columns(2),

                    Tabs\Tab::make('Alasan Memilih Kami')->schema([
                        TextInput::make('page_home.why_title')->label('Judul')->placeholder('Developer Terpercaya'),
                        TextInput::make('page_home.why_subtitle')->label('Sub Judul'),
                        Repeater::make('page_home.reasons')
                            ->label('Kartu Alasan')
                            ->schema([
                                Select::make('icon')->label('Ikon')->options([
                                    'shield' => 'Perisai (Legalitas)',
                                    'badge' => 'Badge (Terverifikasi)',
                                    'coins' => 'Koin (KPR)',
                                    'home' => 'Rumah',
                                    'map' => 'Lokasi',
                                    'clock' => 'Jam',
                                ])->default('shield'),
                                TextInput::make('title')->label('Judul')->required(),
                                TextInput::make('body')->label('Deskripsi')->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Statistik & Peta')->schema([
                        Toggle::make('page_home.show_stats')->label('Tampilkan Statistik')->default(true),
                        Toggle::make('page_home.show_map')->label('Tampilkan Peta')->default(true),
                        TextInput::make('page_home.map_title')->label('Judul Peta')->placeholder('Map Lokasi'),
                    ])->columns(2),
                ]),
            ]);
    }
}
