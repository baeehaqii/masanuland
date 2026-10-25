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
                        TextInput::make('page_about.title')->label('Judul Bagian')->placeholder('Properti yang Bernilai Melintasi Waktu'),
                        Textarea::make('about_text')->label('Tentang Kami')->placeholder('PT. Masanu Bangun Graha mengembangkan hunian dan area komersial...')->rows(5)->columnSpanFull()
                            ->helperText('Dipakai juga di bagian Tentang pada halaman Beranda.'),
                        TagsInput::make('about_points')->label('Poin Keunggulan')->placeholder('Legalitas dan Akad Transparan')->columnSpanFull(),
                        TextInput::make('about_video')->label('Link Video Profil (YouTube)')->placeholder('https://youtube.com/watch?v=xxxxxxxxxxx')->url()->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Arti Nama')->schema([
                        TextInput::make('page_about.name_title')->label('Judul Bagian')->placeholder('Arti Nama MASANU')->columnSpanFull(),
                        Repeater::make('page_about.name_parts')
                            ->label('Suku Kata')
                            ->schema([
                                TextInput::make('word')->label('Kata')->placeholder('MA')->required(),
                                TextInput::make('origin')->label('Asal Kata')->placeholder('Bahasa Arab'),
                                TextInput::make('meaning')->label('Arti')->placeholder('Era / Masa'),
                                TextInput::make('note')->label('Penjelasan')->placeholder('Penjelasan singkat makna suku kata ini'),
                            ])
                            ->columns(2)->defaultItems(0)->columnSpanFull(),
                        TextInput::make('page_about.name_conclusion')->label('Kesimpulan')->placeholder('Era keberkahan dan kebaikan yang melintasi waktu')->columnSpanFull(),
                    ]),

                    Tabs\Tab::make('Visi & Misi')->schema([
                        TextInput::make('page_about.visi_title')->label('Judul Bagian')->placeholder('Visi & Misi'),
                        TextInput::make('page_about.misi_title')->label('Judul Misi')->placeholder('Misi Perusahaan'),
                        Textarea::make('page_about.visi')->label('Visi')->placeholder('Menjadi pengembang properti terpercaya di Jawa Tengah...')->rows(3)->columnSpanFull(),
                        TextInput::make('page_about.visi_label')->label('Label di Bawah Visi')->placeholder('Visi Perusahaan'),
                        Repeater::make('page_about.misi')
                            ->label('Misi')
                            ->schema([
                                TextInput::make('title')->label('Judul')->placeholder('Membangun hunian berkualitas')->required(),
                                Textarea::make('body')->label('Penjelasan')->placeholder('Penjelasan singkat misi ini')->rows(3),
                            ])
                            ->defaultItems(0)->columnSpanFull(),
                    ])->columns(2),

                    Tabs\Tab::make('Budaya Kerja')->schema([
                        TextInput::make('page_about.budaya_title')->label('Judul Bagian')->placeholder('Budaya Kerja'),
                        Textarea::make('page_about.budaya_subtitle')->label('Sub Judul')->placeholder('Nilai yang kami pegang setiap hari')->rows(2),
                        Repeater::make('page_about.budaya')
                            ->label('Nilai Budaya Kerja')
                            ->schema([
                                TextInput::make('letter')->label('Huruf')->placeholder('A')->maxLength(2)->required(),
                                TextInput::make('title')->label('Judul')->placeholder('Amanah')->required(),
                                TextInput::make('english')->label('Istilah Inggris')->placeholder('Trustworthy'),
                                Textarea::make('body')->label('Penjelasan')->placeholder('Penjelasan singkat nilai ini')->rows(2)->columnSpanFull(),
                            ])
                            ->columns(3)->defaultItems(0)->columnSpanFull(),
                    ]),

                    Tabs\Tab::make('Statistik')->schema([
                        TextInput::make('page_about.stats_title')->label('Judul Bagian')->placeholder('Angka Kami'),
                        Repeater::make('stats')
                            ->label('Statistik')
                            ->helperText('Dipakai di halaman Beranda dan Tentang Kami.')
                            ->schema([
                                TextInput::make('value')->label('Angka')->placeholder('500+')->required(),
                                TextInput::make('label')->label('Keterangan')->placeholder('Rumah Terjual')->required(),
                            ])
                            ->columns(2)->defaultItems(0)->columnSpanFull(),
                    ]),
                ]),
            ]);
    }
}
