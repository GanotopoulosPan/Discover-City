<?php

namespace App\Filament\Resources\Sights\Pages;

use App\Filament\Resources\Sights\SightResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSights extends ListRecords
{
    protected static string $resource = SightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
