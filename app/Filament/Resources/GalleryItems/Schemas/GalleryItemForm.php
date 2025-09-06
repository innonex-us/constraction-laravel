<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(12)->schema([
                    TextInput::make('title')->required()->columnSpan(8),
                    TextInput::make('slug')->disabled()->dehydrated(false)->columnSpan(4),
                    TextInput::make('category')->columnSpan(4),
                    Textarea::make('caption')->rows(3)->columnSpan(8),
                    FileUpload::make('image')
                        ->image()
                        ->directory('gallery')
                        ->disk('public')
                        ->imageEditor()
                        ->required()
                        ->columnSpan(6),
                    TextInput::make('order')->numeric()->default(0)->columnSpan(3),
                    Toggle::make('is_published')->default(true)->columnSpan(3),
                ]),
            ]);
    }
}

