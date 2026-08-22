<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SeoSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $title = 'SEO & Open Graph';

    protected static ?string $navigationLabel = 'SEO & OG';

    protected static ?int $navigationSort = 2;

    /** @return array<int, string> */
    protected function settingKeys(): array
    {
        return ['seo', 'og'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('SEO')
                    ->description('Dipakai mesin pencari. Judul ideal ±60 karakter, deskripsi ±155 karakter.')
                    ->schema([
                        TextInput::make('seo.title')->label('Judul Beranda')->maxLength(70),
                        TextInput::make('seo.title_suffix')->label('Akhiran Judul Halaman')
                            ->helperText('Contoh: "Masanuland" → "Tentang Kami — Masanuland".'),
                        Textarea::make('seo.description')->label('Meta Description')->rows(3)->maxLength(200)->columnSpanFull(),
                        TagsInput::make('seo.keywords')->label('Keywords')
                            ->placeholder('perumahan purbalingga')
                            ->helperText('Tekan Enter tiap selesai satu kata kunci.')
                            ->columnSpanFull(),
                        Select::make('seo.robots')->label('Robots')->options([
                            'index, follow' => 'index, follow (tampil di Google)',
                            'noindex, nofollow' => 'noindex, nofollow (sembunyikan)',
                        ])->default('index, follow'),
                        TextInput::make('seo.canonical')->label('Canonical URL')->url(),
                    ])->columns(2)->columnSpanFull(),

                Section::make('Open Graph / Social Share')
                    ->description('Tampilan saat link dibagikan di WhatsApp, Facebook, dan X.')
                    ->schema([
                        TextInput::make('og.title')->label('OG Title'),
                        TextInput::make('og.site_name')->label('OG Site Name'),
                        Textarea::make('og.description')->label('OG Description')->rows(3)->columnSpanFull(),
                        FileUpload::make('og.image')->label('OG Image')
                            ->helperText('Rasio 1200x630 px.')
                            ->image()->directory('site')->columnSpanFull(),
                        Select::make('og.type')->label('OG Type')->options([
                            'website' => 'website',
                            'article' => 'article',
                        ])->default('website'),
                        Select::make('og.twitter_card')->label('Twitter Card')->options([
                            'summary_large_image' => 'summary_large_image',
                            'summary' => 'summary',
                        ])->default('summary_large_image'),
                    ])->columns(2)->columnSpanFull(),
            ]);
    }
}
