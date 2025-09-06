<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('location'),
                TextEntry::make('client'),
                TextEntry::make('status'),
                TextEntry::make('category'),
                ImageEntry::make('featured_image'),
                TextEntry::make('started_at')
                    ->date(),
                TextEntry::make('completed_at')
                    ->date(),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('meta_title'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
