<?php

namespace App\Filament\Resources\Careers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CareerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('location'),
                TextInput::make('department'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('apply_url'),
                Toggle::make('is_open')
                    ->required(),
                DatePicker::make('posted_at'),
            ]);
    }
}
