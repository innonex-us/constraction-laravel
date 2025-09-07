<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('site_name'),
                FileUpload::make('logo_path')
                    ->label('Logo')
                    ->image()
                    ->disk('public')
                    ->directory('site')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([null, '1:1', '16:9', '4:1'])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('500')
                    ->imageResizeTargetHeight('200')
                    ->previewable(true)
                    ->imagePreviewHeight('120'),
                TextInput::make('primary_color'),
                TextInput::make('secondary_color'),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('headline'),
                TextInput::make('hero_video_url'),
                Textarea::make('social_links')
                    ->columnSpanFull(),
                TextInput::make('theme')
                    ->required()
                    ->default('default'),
            ]);
    }
}
