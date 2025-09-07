<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessages extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $heading = 'Recent Contact Messages';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContactMessage::query()->latest()
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->columns([
                TextColumn::make('name')->label('From')->searchable()->limit(24),
                TextColumn::make('subject')->searchable()->limit(30),
                TextColumn::make('status')->badge()
                    ->colors([
                        'warning' => 'new',
                        'info' => 'reviewing',
                        'success' => 'resolved',
                        'gray' => fn ($state) => blank($state),
                    ])->label('Status'),
                TextColumn::make('created_at')->dateTime()->since()->label('Received'),
            ]);
    }
}
