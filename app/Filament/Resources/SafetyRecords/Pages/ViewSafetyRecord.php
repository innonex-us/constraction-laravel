<?php

namespace App\Filament\Resources\SafetyRecords\Pages;

use App\Filament\Resources\SafetyRecords\SafetyRecordResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSafetyRecord extends ViewRecord
{
    protected static string $resource = SafetyRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
