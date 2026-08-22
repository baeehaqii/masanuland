<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama')->required(),
            TextInput::make('location')->label('Lokasi / Keterangan'),
            Select::make('project_id')->label('Perumahan')->relationship('project', 'name')->searchable(),
            TextInput::make('video_url')->label('Link Video (YouTube)')->url(),
            FileUpload::make('image')->label('Foto')->image()->directory('testimonials'),
            TextInput::make('sort')->label('Urutan')->numeric()->default(0),
            Textarea::make('content')->label('Testimoni')->rows(4)->columnSpanFull(),
            Toggle::make('is_published')->label('Tayang')->default(true),
        ])->columns(2);
    }
}
