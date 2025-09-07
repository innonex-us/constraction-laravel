<?php

namespace App\Filament\Widgets;

use App\Models\Prequalification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPrequalifications extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Recent Prequalifications';

    public function table(Table $table): Table
    {
        return $table
            ->query(Prequalification::query()->latest())
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->columns([
                TextColumn::make('company_name')->label('Company')->searchable()->limit(28),
                TextColumn::make('trade')->label('Trade')->searchable()->limit(24),
                TextColumn::make('created_at')->since()->label('Submitted'),
            ]);
    }
}

