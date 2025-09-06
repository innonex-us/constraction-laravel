<?php

namespace App\Filament\Resources\GalleryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class GalleryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                ImageColumn::make('image')
                    ->label('Preview')
                    ->disk('public')
                    ->height(48)
                    ->width(64)
                    ->rounded(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category')->sortable(),
                TextColumn::make('order')->label('#')->sortable(),
            ])
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

