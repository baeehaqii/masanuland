<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PageAbout extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $title = 'Halaman Tentang Kami';

    protected static ?string $navigationLabel = 'Tentang Kami';

    protected static ?int $navigationSort = 2;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['about_text', 'about_points', 'about_video', 'stats', 'page_about'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make()->columnSpanFull()->tabs([
                    Tabs\Tab::make('Header')->schema([
                        TextInput::make('page_about.hero_title')->label('Judul Halaman')->placeholder('Tentang Kami'),
                        TextInput::make('page_about.hero_eyebrow')->label('Tagline Atas')->placeholder('Easy Living, Grow The Future'),
                    ])->columns(2),

                    Tabs\Tab::make('Profil')->schema([
                        TextInput::make('page_about.eyebrow')->label('Label Kecil')->placeholder('PT. Masanu Bangun Graha'),
                        TextInput::make('page_about.title')->label('Judul Bagian'),
                        Textarea::make('about_text')->label('Tentang Kami')->rows(5)->columnSpanFull()
                            ->helperText('Dipakai juga di bagian Tentang pada halaman Beranda.'),
                        TagsInput::make('about_points')->label('Poin Keunggulan')->columnSpanFull(),
                        TextInput::make('about_video')->label('Link Video Profil (YouTube)')->url()->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Arti Nama')->schema([
                        TextInput::make('page_about.name_title')->label('Judul Bagian')->columnSpanFull(),
                        Repeater::make('page_about.name_parts')
                            ->label('Suku Kata')
                            ->schema([
                                TextInput::make('word')->label('Kata')->required(),
                                TextInput::make('origin')->label('Asal Kata'),
                                TextInput::make('meaning')->label('Arti'),
                                TextInput::make('note')->label('Penjelasan'),
                            ])
                            ->columns(2)->defaultItems(0)->columnSpanFull(),
                        TextInput::make('page_about.name_conclusion')->label('Kesimpulan')->columnSpanFull(),
                    ]),

                    Tabs\Tab::make('Visi & Misi')->schema([
                        TextInput::make('page_about.visi_title')->label('Judul Bagian')->placeholder('Visi & Misi'),
                        TextInput::make('page_about.misi_title')->label('Judul Misi')->placeholder('Misi Perusahaan'),
                        Textarea::make('page_about.visi')->label('Visi')->rows(3)->columnSpanFull(),
                        TextInput::make('page_about.visi_label')->label('Label di Bawah Visi')->placeholder('Visi Perusahaan'),
                        Repeater::make('page_about.misi')
                            ->label('Misi')
                            ->schema([
                                TextInput::make('title')->label('Judul')->required(),
                                Textarea::make('body')->label('Penjelasan')->rows(3),
                            ])
                            ->defaultItems(0)->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Budaya Kerja')->schema([
                        TextInput::make('page_about.budaya_title')->label('Judul Bagian'),
                        Textarea::make('page_about.budaya_subtitle')->label('Sub Judul')->rows(2),
                        Repeater::make('page_about.budaya')
                            ->label('Nilai Budaya Kerja')
                            ->schema([
                                TextInput::make('letter')->label('Huruf')->maxLength(2)->required(),
                                TextInput::make('title')->label('Judul')->required(),
                                TextInput::make('english')->label('Istilah Inggris'),
                                Textarea::make('body')->label('Penjelasan')->rows(2)->columnSpanFull(),
                            ])
                            ->columns(3)->defaultItems(0)->columnSpanFull(),
                    ]),

                    Tabs\Tab::make('Statistik')->schema([
                        TextInput::make('page_about.stats_title')->label('Judul Bagian')->placeholder('Angka Kami'),
                        Repeater::make('stats')
                            ->label('Statistik')
                            ->helperText('Dipakai di halaman Beranda dan Tentang Kami.')
                            ->schema([
                                TextInput::make('value')->label('Angka')->required(),
                                TextInput::make('label')->label('Keterangan')->required(),
                            ])
                            ->columns(2)->defaultItems(0)->columnSpanFull(),
                    ]),
                ]),
            ]);
    }
}
