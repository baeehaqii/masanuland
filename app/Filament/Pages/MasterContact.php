<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MasterContact extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $title = 'Kontak & Sosial Media';

    protected static ?string $navigationLabel = 'Kontak & Sosial';

    protected static ?int $navigationSort = 3;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['phone', 'whatsapp', 'whatsapp_text', 'email', 'address', 'socials', 'map_embed'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Kontak')->schema([
                    TextInput::make('phone')->label('Nomor Telepon')->placeholder('0812-3456-7890'),
                    TextInput::make('whatsapp')->label('Nomor WhatsApp')->placeholder('6281234567890'),
                    TextInput::make('email')->label('Email')->email(),
                    TextInput::make('whatsapp_text')->label('Pesan WhatsApp Default'),
                    Textarea::make('address')->label('Alamat')->rows(2)->columnSpanFull(),
                    Textarea::make('map_embed')->label('Embed Google Maps')->rows(3)->columnSpanFull()
                        ->helperText('Tempel kode <iframe> dari Google Maps (Bagikan → Sematkan peta), atau URL embed-nya saja.'),
                ])->columns(2)->columnSpanFull(),

                Section::make('Sosial Media')->schema([
                    Repeater::make('socials')
                        ->hiddenLabel()
                        ->schema([
                            TextInput::make('label')->label('Nama')->required()->placeholder('Instagram'),
                            TextInput::make('url')->label('Link')->url()->required(),
                        ])
                        ->columns(2)->defaultItems(0)->columnSpanFull(),
                ])->columnSpanFull(),
            ]);
    }
}
