<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MasterIdentity extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $title = 'Identitas & Logo';

    protected static ?string $navigationLabel = 'Identitas & Logo';

    protected static ?int $navigationSort = 1;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['brand_name', 'tagline', 'logo', 'logo_footer', 'favicon'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Identitas')->schema([
                    TextInput::make('brand_name')->label('Nama Brand')->required(),
                    Textarea::make('tagline')->label('Tagline / Deskripsi Singkat')->rows(3)->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),

                Section::make('Logo')->schema([
                    FileUpload::make('logo')->label('Logo Navbar')
                        ->helperText('Tampil di menu atas. PNG/SVG transparan, tinggi ±80 px.')
                        ->image()->directory('site'),
                    FileUpload::make('logo_footer')->label('Logo Footer')
                        ->helperText('Versi terang untuk latar maroon. Kosongkan untuk memakai logo navbar.')
                        ->image()->directory('site'),
                    FileUpload::make('favicon')->label('Favicon')
                        ->helperText('Ikon tab browser. PNG/ICO/SVG persegi, 512x512 px.')
                        ->image()->directory('site'),
                ])->columns(3)->columnSpanFull(),
            ]);
    }
}
