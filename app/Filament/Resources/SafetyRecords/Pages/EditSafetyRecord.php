<?php

namespace App\Filament\Resources\SafetyRecords\Pages;

use App\Filament\Resources\SafetyRecords\SafetyRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSafetyRecord extends EditRecord
{
    protected static string $resource = SafetyRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
