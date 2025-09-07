<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('content')
                    ->columnSpanFull(),
                FileUpload::make('hero_image')
                    ->image()
                    ->disk('public')
                    ->directory('pages')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->afterStateUpdated(function ($state) { if ($state) \App\Support\ImageHelper::generateVariants($state); }),
                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}
