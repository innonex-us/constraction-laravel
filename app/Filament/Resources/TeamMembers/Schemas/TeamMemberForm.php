<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('role'),
                Textarea::make('bio')
                    ->columnSpanFull(),
                FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->directory('team')
                    ->imageEditor()
                    ->afterStateUpdated(function ($state) { if ($state) \App\Support\ImageHelper::generateVariants($state); }),
                TextInput::make('linkedin_url'),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
