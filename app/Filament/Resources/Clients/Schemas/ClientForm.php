<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Leave blank to auto-generate from name'),
                FileUpload::make('logo')
                    ->label('Client Logo')
                    ->image()
                    ->disk('public')
                    ->directory('clients')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([null, '16:9', '4:1', '2:1'])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('300')
                    ->imageResizeTargetHeight('120')
                    ->previewable(true)
                    ->imagePreviewHeight('80'),
                TextInput::make('website_url')
                    ->label('Website URL')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://example.com'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
