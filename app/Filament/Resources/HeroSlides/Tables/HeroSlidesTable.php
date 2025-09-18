<?php

namespace App\Filament\Resources\HeroSlides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class HeroSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Preview')
                    ->size(80),
                    
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('subtitle')
                    ->searchable()
                    ->limit(50),
                    
                TextColumn::make('button_text')
                    ->label('CTA Button')
                    ->badge()
                    ->color(fn ($record) => match($record->button_style) {
                        'primary' => 'success',
                        'secondary' => 'gray',
                        'outline' => 'info',
                        default => 'gray'
                    }),
                    
                TextColumn::make('order')
                    ->sortable()
                    ->alignCenter(),
                    
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
