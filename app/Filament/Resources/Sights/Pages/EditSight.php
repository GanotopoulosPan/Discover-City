<?php

namespace App\Filament\Resources\Sights\Pages;

use App\Filament\Resources\Sights\SightResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSight extends EditRecord
{
    protected static string $resource = SightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
