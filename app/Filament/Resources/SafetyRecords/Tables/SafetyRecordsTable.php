<?php

namespace App\Filament\Resources\SafetyRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SafetyRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')
                    ->label('Year')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('emr')
                    ->label('EMR')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($state) => $state < 1.0 ? 'success' : ($state < 1.5 ? 'warning' : 'danger'))
                    ->description(fn ($state) => $state < 1.0 ? 'Excellent' : ($state < 1.5 ? 'Good' : 'Needs Improvement')),
                TextColumn::make('trir')
                    ->label('TRIR')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($state) => $state < 2.0 ? 'success' : ($state < 3.0 ? 'warning' : 'danger'))
                    ->description(fn ($state) => $state < 2.0 ? 'Excellent' : ($state < 3.0 ? 'Good' : 'Needs Improvement')),
                TextColumn::make('ltir')
                    ->label('LTIR')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($state) => $state < 1.0 ? 'success' : ($state < 2.0 ? 'warning' : 'danger'))
                    ->toggleable(),
                TextColumn::make('total_hours')
                    ->label('Total Hours')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->toggleable(),
                TextColumn::make('osha_recordables')
                    ->label('OSHA Recordables')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => $state == 0 ? 'success' : ($state < 5 ? 'warning' : 'danger')),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('year')
                    ->options(fn () => range(date('Y'), date('Y') - 10))
                    ->placeholder('All Years'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('year', 'desc');
    }
}
