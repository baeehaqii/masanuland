<?php

namespace App\Filament\Resources\Shortlinks\Pages;

use App\Filament\Resources\Shortlinks\ShortlinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShortlink extends EditRecord
{
    protected static string $resource = ShortlinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
