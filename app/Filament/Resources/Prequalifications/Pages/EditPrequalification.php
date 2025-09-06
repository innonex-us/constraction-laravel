<?php

namespace App\Filament\Resources\Prequalifications\Pages;

use App\Filament\Resources\Prequalifications\PrequalificationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPrequalification extends EditRecord
{
    protected static string $resource = PrequalificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
