<?php

namespace App\Filament\Resources\Prequalifications\Pages;

use App\Filament\Resources\Prequalifications\PrequalificationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrequalification extends ViewRecord
{
    protected static string $resource = PrequalificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
