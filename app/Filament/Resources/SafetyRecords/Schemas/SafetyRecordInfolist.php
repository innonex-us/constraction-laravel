<?php

namespace App\Filament\Resources\SafetyRecords\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SafetyRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Safety Metrics')
                    ->schema([
                        TextEntry::make('year')
                            ->label('Year')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('emr')
                            ->label('EMR (Experience Modification Rate)')
                            ->numeric(decimalPlaces: 2)
                            ->badge()
                            ->color(fn ($state) => $state < 1.0 ? 'success' : ($state < 1.5 ? 'warning' : 'danger'))
                            ->formatStateUsing(fn ($state) => $state . ($state < 1.0 ? ' (Excellent)' : ($state < 1.5 ? ' (Good)' : ' (Needs Improvement)'))),
                        TextEntry::make('trir')
                            ->label('TRIR (Total Recordable Incident Rate)')
                            ->numeric(decimalPlaces: 2)
                            ->badge()
                            ->color(fn ($state) => $state < 2.0 ? 'success' : ($state < 3.0 ? 'warning' : 'danger'))
                            ->formatStateUsing(fn ($state) => $state . ($state < 2.0 ? ' (Excellent)' : ($state < 3.0 ? ' (Good)' : ' (Needs Improvement)'))),
                        TextEntry::make('ltir')
                            ->label('LTIR (Lost Time Incident Rate)')
                            ->numeric(decimalPlaces: 2)
                            ->badge()
                            ->color(fn ($state) => $state < 1.0 ? 'success' : ($state < 2.0 ? 'warning' : 'danger'))
                            ->placeholder('Not recorded'),
                    ])->columns(2),

                Section::make('Work Statistics')
                    ->schema([
                        TextEntry::make('total_hours')
                            ->label('Total Work Hours')
                            ->numeric()
                            ->formatStateUsing(fn ($state) => number_format($state) . ' hours'),
                        TextEntry::make('osha_recordables')
                            ->label('OSHA Recordable Incidents')
                            ->numeric()
                            ->badge()
                            ->color(fn ($state) => $state == 0 ? 'success' : ($state < 5 ? 'warning' : 'danger'))
                            ->formatStateUsing(fn ($state) => $state . ' incident' . ($state != 1 ? 's' : '')),
                    ])->columns(2),

                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Description/Notes')
                            ->columnSpanFull()
                            ->placeholder('No additional notes provided'),
                        TextEntry::make('created_at')
                            ->label('Record Created')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }
}
