<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Colours land in the settings row and are printed as CSS custom properties in
 * the site's <head>, overriding the Tailwind theme defaults.
 */
class ThemeSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $title = 'Warna & Tampilan';

    protected static ?string $navigationLabel = 'Warna & Tampilan';

    protected static ?int $navigationSort = 1;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['theme'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Warna Utama')
                    ->description('Warna dominan website. Kosongkan salah satu untuk memakai warna bawaan.')
                    ->schema([
                        ColorPicker::make('theme.maroon')->label('Maroon (Utama)'),
                        ColorPicker::make('theme.gold')->label('Emas (Aksen)'),
                        ColorPicker::make('theme.gold_dark')->label('Emas Gelap (Hover)'),
                        ColorPicker::make('theme.brick')->label('Latar Slider Banner'),
                    ])->columns(4)->columnSpanFull(),

                Section::make('Gradasi Maroon')
                    ->description('Dipakai untuk latar, border, dan teks sekunder.')
                    ->schema([
                        ColorPicker::make('theme.maroon_50')->label('Maroon 50'),
                        ColorPicker::make('theme.maroon_100')->label('Maroon 100'),
                        ColorPicker::make('theme.maroon_200')->label('Maroon 200'),
                        ColorPicker::make('theme.maroon_400')->label('Maroon 400'),
                        ColorPicker::make('theme.maroon_500')->label('Maroon 500'),
                        ColorPicker::make('theme.maroon_700')->label('Maroon 700'),
                        ColorPicker::make('theme.maroon_800')->label('Maroon 800'),
                        ColorPicker::make('theme.maroon_900')->label('Maroon 900'),
                    ])->columns(4)->columnSpanFull(),

                Section::make('Tombol WhatsApp')->schema([
                    ColorPicker::make('theme.wa')->label('Hijau WhatsApp'),
                    ColorPicker::make('theme.wa_dark')->label('Hijau Hover'),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
