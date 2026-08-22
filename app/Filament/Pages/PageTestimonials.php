<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PageTestimonials extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $title = 'Halaman Testimoni';

    protected static ?string $navigationLabel = 'Testimoni';

    protected static ?int $navigationSort = 3;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['page_testimonials'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Header Halaman')->schema([
                    TextInput::make('page_testimonials.hero_title')->label('Judul')->placeholder('Testimoni'),
                    Textarea::make('page_testimonials.hero_subtitle')->label('Sub Judul')->rows(2),
                    TextInput::make('page_testimonials.empty_text')->label('Teks Saat Belum Ada Testimoni')->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
