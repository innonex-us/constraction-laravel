<?php

namespace App\Filament\Resources\Prequalifications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PrequalificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_name')
                    ->label('Contact')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('trade')
                    ->label('Trade')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('years_in_business')
                    ->label('Years in Business')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('emr')
                    ->label('EMR')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('annual_revenue')
                    ->label('Annual Revenue')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('has_documents')
                    ->label('Has Documents')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('documents')),
                Filter::make('recent')
                    ->label('Last 30 Days')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30))),
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
            ->defaultSort('created_at', 'desc');
    }
}
