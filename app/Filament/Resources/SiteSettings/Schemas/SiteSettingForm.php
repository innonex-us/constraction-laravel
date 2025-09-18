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
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Quick Access')->schema([
                    Placeholder::make('hero_slider_info')
                        ->label('🎬 Hero Slider Management')
                        ->content('Hero content is now managed via the dedicated Hero Slider section. Go to Content → Hero Slider to add/edit slides.')
                        ->columnSpanFull(),
                        
                    Placeholder::make('content_management_info')
                        ->label('📝 Content Management')
                        ->content('• About Us: Content → Pages (look for green "About Us" badge)
• Services: Portfolio → Services (now with gallery support)
• Projects: Portfolio → Projects (now with gallery support)
• News: Content → Posts (rich text editor)')
                        ->columnSpanFull(),
                        
                    Placeholder::make('navbar_info')
                        ->label('🧭 Navigation Updates')
                        ->content('Navbar has been updated with dropdowns for Services and Projects. Safety and Partners links have been removed for a cleaner design.')
                        ->columnSpanFull(),
                ])->columnSpanFull(),
                    
                Fieldset::make('Basic Information')->schema([
                    TextInput::make('site_name')
                        ->label('Site Name')
                        ->required(),
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
                    
                    Textarea::make('address')
                        ->label('Company Address')
                        ->columnSpanFull(),
                    TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel(),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->email(),
                ])->columnSpanFull(),
                
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
                Fieldset::make('Fallback Hero Content')->schema([
                    TextInput::make('headline')
                        ->label('Fallback Headline')
                        ->helperText('Used when no hero slides are active'),
                    Textarea::make('subheadline')
                        ->label('Fallback Subheadline')
                        ->helperText('Used when no hero slides are active'),
                    FileUpload::make('hero_image')
                        ->label('Fallback Hero Image')
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
                        ->helperText('Fallback image when no hero slides are active. Recommended size: 1920x1080px')
                        ->columnSpanFull(),
                    TextInput::make('hero_video_url')
                        ->label('Fallback Hero Video URL')
                        ->url()
                        ->helperText('Fallback video when no hero slides are active')
                        ->columnSpanFull(),
                ])->columnSpanFull(),
                
                Placeholder::make('hero_fallback_note')
                    ->label('⚠️ Important Note')
                    ->content('Hero content is now managed via the Hero Slider. These fields are only used as fallback when no slides are active.')
                    ->columnSpanFull(),
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
                    
                    // New sections for the updated homepage
                    Grid::make(1)->schema([
                        Placeholder::make('new_sections_info')
                            ->label('✨ New Homepage Sections')
                            ->content('Your homepage now includes: Company Statistics, About Us Preview, How We Work Process, and Why Choose Us sections. These are automatically displayed and don\'t require additional settings.')
                            ->columnSpanFull(),
                    ]),
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
