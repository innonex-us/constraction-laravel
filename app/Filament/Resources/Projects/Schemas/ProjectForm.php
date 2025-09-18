<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Leave blank to auto-generate from title'),
                Textarea::make('excerpt')
                    ->label('Project Summary')
                    ->helperText('Brief description of the project')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Project Details')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpanFull(),
                TextInput::make('location'),
                TextInput::make('client'),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
                TextInput::make('category'),
                FileUpload::make('featured_image')
                    ->image()
                    ->disk('public')
                    ->directory('projects')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->afterStateUpdated(function ($state) { if ($state) \App\Support\ImageHelper::generateVariants($state); })
                    ->helperText('Main project image'),
                FileUpload::make('gallery')
                    ->label('Project Gallery')
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('projects/gallery')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('1600')
                    ->imageResizeTargetHeight('900')
                    ->maxFiles(15)
                    ->reorderable()
                    ->panelLayout('grid')
                    ->afterStateUpdated(function ($state) { 
                        if ($state && is_array($state)) {
                            foreach ($state as $image) {
                                if ($image) \App\Support\ImageHelper::generateVariants($image);
                            }
                        }
                    })
                    ->helperText('Upload multiple images for the project gallery (max 15 images)')
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
