<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;

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
                Fieldset::make('Brand Colors')->schema([
                    Grid::make(12)->schema([
                        Select::make('primary_color_preset')
                            ->label('Primary Color (Preset)')
                            ->options(self::palette())
                            ->native(false)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) $set('primary_color', $state, shouldCallUpdatedHooks: true);
                            })
                            ->columnSpan(6),
                        ColorPicker::make('primary_color')
                            ->label('Primary Color')
                            ->hex()
                            ->columnSpan(6),

                        Select::make('secondary_color_preset')
                            ->label('Secondary Color (Preset)')
                            ->options(self::palette())
                            ->native(false)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) $set('secondary_color', $state, shouldCallUpdatedHooks: true);
                            })
                            ->columnSpan(6),
                        ColorPicker::make('secondary_color')
                            ->label('Secondary Color')
                            ->hex()
                            ->columnSpan(6),
                    ])->columns(12),
                ]),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('headline'),
                Textarea::make('subheadline'),
                Fieldset::make('Hero Media')->schema([
                    FileUpload::make('hero_image')
                        ->label('Hero Image')
                        ->image()
                        ->disk('public')
                        ->directory('hero')
                        ->imageEditor()
                        ->imageEditorMode(2)
                        ->imageEditorAspectRatios([null, '16:9', '21:9', '3:2'])
                        ->imageResizeMode('cover')
                        ->imageResizeTargetWidth('1920')
                        ->imageResizeTargetHeight('1080')
                        ->previewable(true)
                        ->imagePreviewHeight('200')
                        ->helperText('Recommended size: 1920x1080px')
                        ->columnSpanFull(),
                    TextInput::make('hero_video_url')
                        ->label('Hero Video URL')
                        ->url()
                        ->helperText('Video will take priority over image if both are provided')
                        ->columnSpanFull(),
                ])->columnSpanFull(),
                Fieldset::make('Homepage Stats')->schema([
                    TextInput::make('stat_years')->label('Years'),
                    TextInput::make('stat_projects')->label('Projects'),
                    TextInput::make('stat_emr')->label('Safety EMR'),
                ])->columns(3),
                Fieldset::make('Call to Action')->schema([
                    TextInput::make('cta_heading'),
                    Textarea::make('cta_text')->columnSpanFull(),
                    TextInput::make('cta_button_text'),
                    TextInput::make('cta_button_url'),
                ]),
                Fieldset::make('Homepage Sections')->schema([
                    Grid::make(2)->schema([
                        Toggle::make('show_services_section')
                            ->label('Show Services Section')
                            ->default(true),
                        TextInput::make('services_section_heading')
                            ->label('Services Heading')
                            ->default('Services'),
                        TextInput::make('services_limit')
                            ->label('Services Limit')
                            ->numeric()
                            ->default(6)
                            ->minValue(1)
                            ->maxValue(12),
                    ])->columns(2),
                    Grid::make(2)->schema([
                        Toggle::make('show_projects_section')
                            ->label('Show Projects Section')
                            ->default(true),
                        TextInput::make('projects_section_heading')
                            ->label('Projects Heading')
                            ->default('Featured Projects'),
                        TextInput::make('projects_limit')
                            ->label('Projects Limit')
                            ->numeric()
                            ->default(6)
                            ->minValue(1)
                            ->maxValue(12),
                    ])->columns(2),
                    Grid::make(2)->schema([
                        Toggle::make('show_testimonials_section')
                            ->label('Show Testimonials Section')
                            ->default(true),
                        TextInput::make('testimonials_section_heading')
                            ->label('Testimonials Heading')
                            ->default('What clients say'),
                        TextInput::make('testimonials_limit')
                            ->label('Testimonials Limit')
                            ->numeric()
                            ->default(6)
                            ->minValue(1)
                            ->maxValue(12),
                    ])->columns(2),
                    Grid::make(2)->schema([
                        Toggle::make('show_clients_section')
                            ->label('Show Clients Section')
                            ->default(true),
                        TextInput::make('clients_section_heading')
                            ->label('Clients Heading')
                            ->default('Our Clients'),
                    ])->columns(2),
                    Grid::make(2)->schema([
                        Toggle::make('show_news_section')
                            ->label('Show News Section')
                            ->default(true),
                        TextInput::make('news_section_heading')
                            ->label('News Heading')
                            ->default('Latest News'),
                        TextInput::make('news_limit')
                            ->label('News Limit')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(12),
                    ])->columns(2),
                    Grid::make(2)->schema([
                        Toggle::make('show_badges_section')
                            ->label('Show Badges Section')
                            ->default(true),
                        TextInput::make('badges_section_heading')
                            ->label('Badges Heading')
                            ->default('Certifications & Affiliations'),
                    ])->columns(2),
                ])->columnSpanFull(),
                Textarea::make('social_links')
                    ->columnSpanFull(),
                TextInput::make('theme')
                    ->required()
                    ->default('default'),
            ]);
    }

    protected static function palette(): array
    {
        return [
            '#10B981' => 'Emerald',
            '#14B8A6' => 'Teal',
            '#0EA5E9' => 'Sky',
            '#3B82F6' => 'Blue',
            '#6366F1' => 'Indigo',
            '#8B5CF6' => 'Violet',
            '#D946EF' => 'Fuchsia',
            '#F43F5E' => 'Rose',
            '#F59E0B' => 'Amber',
            '#22C55E' => 'Green',
        ];
    }
}
