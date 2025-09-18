<?php

namespace App\Filament\Resources\HeroSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),
                    
                    TextInput::make('subtitle')
                        ->maxLength(255)
                        ->helperText('Short subtitle or tagline')
                        ->columnSpan(2),
                    
                    Textarea::make('description')
                        ->rows(3)
                        ->helperText('Main description text for the slide')
                        ->columnSpan(2),
                ]),
                
                Fieldset::make('Media')->schema([
                    FileUpload::make('image')
                        ->label('Slide Image')
                        ->image()
                        ->disk('public')
                        ->directory('hero-slides')
                        ->imageEditor()
                        ->imageEditorMode(2)
                        ->imageEditorAspectRatios([null, '16:9', '21:9'])
                        ->imageResizeMode('cover')
                        ->imageResizeTargetWidth('1920')
                        ->imageResizeTargetHeight('1080')
                        ->previewable(true)
                        ->imagePreviewHeight('200')
                        ->required()
                        ->helperText('Recommended size: 1920x1080px')
                        ->columnSpanFull(),
                        
                    TextInput::make('video_url')
                        ->label('Video URL (Optional)')
                        ->url()
                        ->helperText('If provided, video will overlay the image')
                        ->columnSpanFull(),
                ])->columnSpanFull(),
                
                Fieldset::make('Call to Action')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('button_text')
                            ->label('Button Text')
                            ->maxLength(50),
                            
                        TextInput::make('button_url')
                            ->label('Button URL')
                            ->url(),
                            
                        Select::make('button_style')
                            ->label('Button Style')
                            ->options([
                                'primary' => 'Primary (Green)',
                                'secondary' => 'Secondary (White)',
                                'outline' => 'Outline',
                            ])
                            ->default('primary'),
                    ]),
                ])->columnSpanFull(),
                
                Grid::make(3)->schema([
                    TextInput::make('order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first'),
                        
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Only active slides will be shown'),
                ]),
            ]);
    }
}
