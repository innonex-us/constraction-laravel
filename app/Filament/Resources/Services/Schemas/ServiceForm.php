<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->helperText('Leave blank to auto-generate from name'),
                Textarea::make('excerpt')
                    ->label('Service Summary')
                    ->helperText('Brief description of the service')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Content')
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
                TextInput::make('icon'),
                FileUpload::make('image')
                    ->label('Featured Image')
                    ->image()
                    ->disk('public')
                    ->directory('services')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1600')
                    ->imageResizeTargetHeight('900')
                    ->afterStateUpdated(function ($state) { if ($state) \App\Support\ImageHelper::generateVariants($state); })
                    ->helperText('Main image for the service'),
                FileUpload::make('gallery')
                    ->label('Gallery Images')
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('services/gallery')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('1600')
                    ->imageResizeTargetHeight('900')
                    ->maxFiles(10)
                    ->reorderable()
                    ->panelLayout('grid')
                    ->afterStateUpdated(function ($state) { 
                        if ($state && is_array($state)) {
                            foreach ($state as $image) {
                                if ($image) \App\Support\ImageHelper::generateVariants($image);
                            }
                        }
                    })
                    ->helperText('Upload multiple images for the service gallery (max 10 images)')
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),
            ]);
    }
}
