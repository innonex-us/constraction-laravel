<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('excerpt')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->columnSpanFull(),
                TextInput::make('location'),
                TextInput::make('client'),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
                TextInput::make('category'),
                FileUpload::make('featured_image')
                    ->image(),
                Textarea::make('gallery')
                    ->columnSpanFull(),
                DatePicker::make('started_at'),
                DatePicker::make('completed_at'),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),
            ]);
    }
}
