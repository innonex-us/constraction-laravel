<?php

namespace App\Filament\Resources\SafetyRecords\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SafetyRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Safety Record Information')
                    ->schema([
                        TextInput::make('year')
                            ->label('Year')
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y') + 1)
                            ->default(date('Y')),
                        
                        Grid::make(3)->schema([
                            TextInput::make('emr')
                                ->label('EMR (Experience Modification Rate)')
                                ->required()
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0)
                                ->helperText('Target: Below 1.0 is excellent'),
                            TextInput::make('trir')
                                ->label('TRIR (Total Recordable Incident Rate)')
                                ->required()
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0)
                                ->helperText('Industry average: ~2.8'),
                            TextInput::make('ltir')
                                ->label('LTIR (Lost Time Incident Rate)')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0)
                                ->helperText('Target: Below 1.0'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('total_hours')
                                ->label('Total Work Hours')
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->helperText('Total hours worked by all employees'),
                            TextInput::make('osha_recordables')
                                ->label('OSHA Recordable Incidents')
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->helperText('Number of OSHA recordable incidents'),
                        ]),

                        Textarea::make('description')
                            ->label('Description/Notes')
                            ->rows(4)
                            ->helperText('Additional safety information, initiatives, or explanations')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }
}
