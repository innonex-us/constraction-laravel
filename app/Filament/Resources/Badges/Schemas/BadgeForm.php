<?php

namespace App\Filament\Resources\Badges\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;

class BadgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(12)->schema([
                    TextInput::make('name')->required()->columnSpan(6),
                    TextInput::make('slug')->disabled()->dehydrated(false)->columnSpan(6),
                    TextInput::make('url')->columnSpan(6),
                    TextInput::make('order')->numeric()->default(0)->columnSpan(3),
                    Toggle::make('is_active')->default(true)->columnSpan(3),
                    FileUpload::make('image')
                        ->image()
                        ->directory('badges')
                        ->disk('public')
                        ->imageEditor()
                        ->imageEditorMode(2)
                        ->imageEditorAspectRatios([null, '1:1', '4:3', '16:9'])
                        ->imageResizeMode('contain')
                        ->imageResizeTargetWidth('800')
                        ->imageResizeTargetHeight('600')
                        ->required()
                        ->columnSpan(6),
                ]),
            ]);
    }
}
