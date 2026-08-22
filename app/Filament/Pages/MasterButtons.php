<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MasterButtons extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCursorArrowRays;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $title = 'Tombol & CTA';

    protected static ?string $navigationLabel = 'Tombol & CTA';

    protected static ?int $navigationSort = 2;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['buttons', 'brochure_url'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Tombol Utama')->schema([
                    TextInput::make('buttons.whatsapp_label')->label('Tombol WhatsApp (Desktop)')->placeholder('WhatsApp'),
                    TextInput::make('buttons.whatsapp_mobile_label')->label('Tombol WhatsApp (Mobile)')->placeholder('Hubungi via WhatsApp'),
                    TextInput::make('buttons.brochure_label')->label('Tombol Brosur')->placeholder('Brosur & Harga'),
                    TextInput::make('buttons.detail_label')->label('Tombol Detail Perumahan')->placeholder('Lihat Detail'),
                    TextInput::make('brochure_url')->label('Link Brosur & Harga')->url()
                        ->helperText('Kosongkan untuk mengarahkan ke WhatsApp.')->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),

                Section::make('CTA Bawah Halaman')->schema([
                    TextInput::make('buttons.cta_title')->label('Judul CTA')->placeholder('Info Lebih Lanjut, Klik'),
                    TextInput::make('buttons.cta_label')->label('Teks Tombol CTA')
                        ->helperText('Kosongkan untuk memakai nomor telepon.'),
                ])->columns(2)->columnSpanFull(),

                Section::make('Judul Kolom Footer')->schema([
                    TextInput::make('buttons.footer_menu_title')->label('Judul Kolom Menu')->placeholder('Menu'),
                    TextInput::make('buttons.footer_contact_title')->label('Judul Kolom Kontak')->placeholder('Kontak Kami'),
                    TextInput::make('buttons.footer_social_title')->label('Judul Kolom Sosial')->placeholder('Ikuti Kami'),
                    TextInput::make('buttons.copyright')->label('Teks Copyright')
                        ->helperText('Kosongkan untuk memakai "Copyright © tahun Nama Brand".')->columnSpanFull(),
                ])->columns(3)->columnSpanFull(),
            ]);
    }
}
