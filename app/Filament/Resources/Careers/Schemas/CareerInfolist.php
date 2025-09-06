<?php

namespace App\Filament\Resources\Careers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CareerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('location'),
                TextEntry::make('department'),
                TextEntry::make('apply_url'),
                IconEntry::make('is_open')
                    ->boolean(),
                TextEntry::make('posted_at')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
