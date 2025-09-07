<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('author_name')
                    ->required(),
                TextInput::make('author_title'),
                TextInput::make('company'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->numeric(),
                FileUpload::make('avatar_image')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('testimonials')
                    ->imageEditor()
                    ->circleCropper()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios(['1:1'])
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('600')
                    ->imageResizeTargetHeight('600')
                    ->previewable(true)
                    ->imagePreviewHeight('96'),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
