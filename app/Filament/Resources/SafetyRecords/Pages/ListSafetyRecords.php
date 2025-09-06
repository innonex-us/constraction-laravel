<?php

namespace App\Filament\Resources\SafetyRecords\Pages;

use App\Filament\Resources\SafetyRecords\SafetyRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSafetyRecords extends ListRecords
{
    protected static string $resource = SafetyRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
