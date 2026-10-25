<?php

namespace App\Filament\Resources\Shortlinks\Pages;

use App\Filament\Resources\Shortlinks\ShortlinkResource;
use Filament\Resources\Pages\ListRecords;

class ListShortlinks extends ListRecords
{
    protected static string $resource = ShortlinkResource::class;
}
