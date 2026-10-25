<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Support\CompressedImage;
use Closure;
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
use Filament\Support\RawJs;
use Illuminate\Http\UploadedFile;
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
                    static::rupiah('price_from', 'Harga Mulai'),
                    static::rupiah('price_before', 'Harga Sebelum Diskon'),
                    TextInput::make('price_note')->label('Catatan Harga')->placeholder('*Hemat 25jt khusus 10 pembeli pertama'),
                    TagsInput::make('badges')->label('Badge Unggulan')->placeholder('120 Unit')->columnSpanFull(),
                    TextInput::make('sort')->label('Urutan')->numeric()->default(0),
                    Toggle::make('is_published')->label('Tampilkan di website')->default(true),
                ])->columns(2),

                Tabs\Tab::make('Media')->schema([
                    FileUpload::make('card_image')->label('Gambar Kartu')->image()->directory('projects')->saveUploadedFileUsing(CompressedImage::store(...)),
                    FileUpload::make('hero_image')->label('Gambar Hero')->image()->directory('projects')->saveUploadedFileUsing(CompressedImage::store(...)),
                    FileUpload::make('site_plan_image')->label('Site Plan')->image()->directory('projects')->saveUploadedFileUsing(CompressedImage::store(...)),
                    FileUpload::make('gallery')->label('Galeri / Update Terkini')
                        ->image()->multiple()->reorderable()->panelLayout('grid')->directory('projects')->saveUploadedFileUsing(CompressedImage::store(...))->columnSpanFull(),
                    TextInput::make('update_video')->label('Video Update Terkini (YouTube)')
                        ->url()->columnSpanFull(),
                    Repeater::make('progress_videos')
                        ->label('Video Progres Pembangunan (YouTube Shorts)')
                        ->helperText('Tampil di bawah section Lokasi, 5 video per baris. Urutkan dengan menggeser.')
                        ->schema([
                            TextInput::make('url')->label('Link YouTube Shorts')->url()->required()
                                ->placeholder('https://youtube.com/shorts/xxxxxxxxxxx'),
                            TextInput::make('label')->label('Keterangan Progres')->required()
                                ->placeholder('Progres Oktober 2026'),
                        ])
                        ->columns(2)
                        ->reorderable()
                        ->defaultItems(0)
                        ->columnSpanFull(),
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
                    Repeater::make('features')
                        ->label('Fasilitas & Keunggulan')
                        ->schema([
                            FileUpload::make('icon')->label('Ikon (SVG)')
                                ->acceptedFileTypes(['image/svg+xml'])
                                ->rules([static::safeSvg()])
                                ->directory('icons'),
                            TextInput::make('label')->label('Keterangan')->required()->placeholder('One Gate System'),
                        ])
                        ->columns(2)
                        ->grid(2)
                        ->reorderable()
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),

                Tabs\Tab::make('Lokasi & Brosur')->schema([
                    TextInput::make('location_video')->label('Video Lokasi (YouTube)')->url(),
                    Textarea::make('map_embed')->label('Embed Google Maps')->rows(3)
                        ->helperText('Tempel kode <iframe> dari Google Maps, atau URL embed-nya saja.'),
                    TextInput::make('map_url')->label('Link Google Maps')->url(),
                    TextInput::make('brochure_url')->label('Link Brosur')->url(),
                ]),
            ]),

            Section::make()->schema([])->hidden(),
        ]);
    }

    /**
     * SVG bisa membawa JavaScript yang jalan kalau file-nya dibuka langsung
     * dari /storage, jadi tolak yang berisi script, event handler, atau link javascript:.
     */
    public static function safeSvg(): Closure
    {
        return static function (string $attribute, mixed $value, Closure $fail): void {
            if (! $value instanceof UploadedFile) {
                return;
            }

            if (preg_match('/<script|\son\w+\s*=|javascript:|<foreignObject/i', (string) file_get_contents($value->getRealPath()))) {
                $fail('SVG mengandung script dan tidak boleh diunggah.');
            }
        };
    }

    /** Input harga: dipisah titik saat diketik, disimpan sebagai angka bulat. */
    protected static function rupiah(string $name, string $label): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->prefix('Rp')
            ->numeric()
            ->mask(RawJs::make("\$money(\$input, ',', '.', 0)"))
            ->stripCharacters('.');
    }
}
