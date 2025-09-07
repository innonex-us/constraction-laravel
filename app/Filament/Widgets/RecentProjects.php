<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentProjects extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Recent Projects';

    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query()->latest())
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->columns([
                TextColumn::make('title')->searchable()->limit(28),
                TextColumn::make('status')->badge()->colors([
                    'warning' => 'planned',
                    'info' => 'in_progress',
                    'success' => 'completed',
                    'gray' => fn ($state) => blank($state),
                ])->label('Status'),
                TextColumn::make('created_at')->since()->label('Created'),
            ]);
    }
}
