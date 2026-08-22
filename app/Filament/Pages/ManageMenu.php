<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageMenu extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?string $title = 'Menu Navigasi & Footer';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?int $navigationSort = 2;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['menu_header', 'menu_footer'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Menu Navigasi (Header)')
                    ->description('Urutan menu di bar atas website. Seret untuk mengurutkan.')
                    ->schema([static::menuRepeater('menu_header')])
                    ->columnSpanFull(),

                Section::make('Menu Footer')
                    ->description('Kolom "Menu" pada footer website.')
                    ->schema([static::menuRepeater('menu_footer')])
                    ->columnSpanFull(),
            ]);
    }

    protected static function menuRepeater(string $name): Repeater
    {
        return Repeater::make($name)
            ->hiddenLabel()
            ->schema([
                TextInput::make('label')->label('Judul Menu')->required(),
                Select::make('type')->label('Jenis')
                    ->options([
                        'link' => 'Link biasa',
                        'projects' => 'Daftar Perumahan (otomatis)',
                        'brochure' => 'Brosur & Harga',
                    ])
                    ->default('link')->required()->live(),
                TextInput::make('url')->label('URL')
                    ->placeholder('/tentang-kami')
                    ->helperText('Contoh: /, /tentang-kami, /testimoni, atau link penuh.')
                    ->visible(fn ($get) => $get('type') !== 'brochure')
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->reorderable()
            ->collapsible()
            ->collapsed()
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->defaultItems(0)
            ->addActionLabel('Tambah menu')
            ->columnSpanFull();
    }
}
