<?php

namespace App\Filament\Resources\Prequalifications\Pages;

use App\Filament\Resources\Prequalifications\PrequalificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrequalifications extends ListRecords
{
    protected static string $resource = PrequalificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
