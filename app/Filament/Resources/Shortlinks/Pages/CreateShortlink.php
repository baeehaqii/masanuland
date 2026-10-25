<?php

namespace App\Filament\Resources\Shortlinks\Pages;

use App\Filament\Resources\Shortlinks\ShortlinkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShortlink extends CreateRecord
{
    protected static string $resource = ShortlinkResource::class;
}
