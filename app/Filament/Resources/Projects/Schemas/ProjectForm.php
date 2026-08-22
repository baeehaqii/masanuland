<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->columnSpanFull()->tabs([
                Tabs\Tab::make('Umum')->schema([
                    TextInput::make('name')
                        ->label('Nama Perumahan')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug((string) $state))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    TextInput::make('tagline')->label('Tagline')->placeholder('5 Menit ke Kota Purwokerto'),
                    TextInput::make('location')->label('Lokasi')->placeholder('Sokaraja, Banyumas'),
                    TextInput::make('price_from')->label('Harga Mulai (Rp)')->numeric(),
                    TextInput::make('price_before')->label('Harga Sebelum Diskon (Rp)')->numeric(),
                    TextInput::make('price_note')->label('Catatan Harga')->placeholder('*Hemat 25jt khusus 10 pembeli pertama'),
                    TagsInput::make('badges')->label('Badge Unggulan')->placeholder('120 Unit')->columnSpanFull(),
                    TextInput::make('sort')->label('Urutan')->numeric()->default(0),
                    Toggle::make('is_published')->label('Tampilkan di website')->default(true),
                ])->columns(2),

                Tabs\Tab::make('Media')->schema([
                    FileUpload::make('card_image')->label('Gambar Kartu')->image()->directory('projects'),
                    FileUpload::make('hero_image')->label('Gambar Hero')->image()->directory('projects'),
                    FileUpload::make('site_plan_image')->label('Site Plan')->image()->directory('projects'),
                    FileUpload::make('gallery')->label('Galeri / Update Terkini')
                        ->image()->multiple()->reorderable()->directory('projects')->columnSpanFull(),
                    TextInput::make('update_video')->label('Video Update Terkini (YouTube)')
                        ->url()->columnSpanFull(),
                ])->columns(3),

                Tabs\Tab::make('Detail')->schema([
                    RichEditor::make('description')->label('Deskripsi')->columnSpanFull(),
                    Repeater::make('distances')
                        ->label('Jarak ke Fasilitas')
                        ->schema([
                            TextInput::make('minutes')->label('Menit')->numeric()->required(),
                            TextInput::make('place')->label('Ke')->required()->placeholder('Alun-Alun Purwokerto'),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->columnSpanFull(),
                    TagsInput::make('features')->label('Fasilitas & Keunggulan')
                        ->placeholder('One Gate System')->columnSpanFull(),
                ]),

                Tabs\Tab::make('Lokasi & Brosur')->schema([
                    TextInput::make('location_video')->label('Video Lokasi (YouTube)')->url(),
                    Textarea::make('map_embed')->label('Embed Google Maps (iframe src)')->rows(3),
                    TextInput::make('map_url')->label('Link Google Maps')->url(),
                    TextInput::make('brochure_url')->label('Link Brosur')->url(),
                ]),
            ]),

            Section::make()->schema([])->hidden(),
        ]);
    }
}
