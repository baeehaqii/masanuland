<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Judul section & label tombol di halaman detail perumahan. Isinya sama untuk
 * semua perumahan — data tiap perumahan diatur di Proyek → Perumahan.
 */
class PageProject extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $title = 'Halaman Detail Perumahan';

    protected static ?string $navigationLabel = 'Detail Perumahan';

    protected static ?int $navigationSort = 3;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['page_project'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Harga & Tombol')->schema([
                    TextInput::make('page_project.price_label')->label('Label Harga')->placeholder('Harga Mulai'),
                    TextInput::make('page_project.price_empty')->label('Teks Saat Harga Kosong')->placeholder('Hubungi CS'),
                    TextInput::make('page_project.contact_label')->label('Tombol Hubungi')->placeholder('Hubungi Kami'),
                    TextInput::make('page_project.brochure_label')->label('Tombol Brosur')->placeholder('Download Brosur'),
                ])->columns(2)->columnSpanFull(),

                Section::make('Judul Section')->schema([
                    TextInput::make('page_project.features_title')->label('Fasilitas')->placeholder('Fasilitas & Keunggulan'),
                    TextInput::make('page_project.siteplan_title')->label('Siteplan')->placeholder('Siteplan & Denah'),
                    TextInput::make('page_project.siteplan_tab')->label('Nama Tab Siteplan')->placeholder('Siteplan'),
                    TextInput::make('page_project.types_tab')->label('Nama Tab Denah')->placeholder('Denah & Tipe'),
                    TextInput::make('page_project.update_title')->label('Update Terkini')->placeholder('Update Terkini'),
                    TextInput::make('page_project.location_title')->label('Lokasi')->placeholder('Lokasi'),
                ])->columns(2)->columnSpanFull(),

                Section::make('Lain-lain')->schema([
                    TextInput::make('page_project.minutes_suffix')->label('Satuan Jarak')->placeholder('Menit')
                        ->helperText('Tampil sebagai "5 Menit ke Pasar Sokaraja".'),
                    TextInput::make('page_project.maps_label')->label('Tombol Buka Peta')->placeholder('Buka di Google Maps'),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
