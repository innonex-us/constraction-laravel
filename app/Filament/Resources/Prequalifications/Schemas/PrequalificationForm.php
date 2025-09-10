<?php

namespace App\Filament\Resources\Prequalifications\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PrequalificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company Information')
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255),
                        Grid::make(2)->schema([
                            TextInput::make('contact_name')
                                ->label('Contact Name')
                                ->maxLength(255),
                            TextInput::make('trade')
                                ->label('Primary Trade')
                                ->maxLength(255),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(255),
                        ]),
                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255),
                    ])->columnSpanFull(),

                Section::make('Business Details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('license_number')
                                ->label('License Number')
                                ->maxLength(255),
                            TextInput::make('years_in_business')
                                ->label('Years in Business')
                                ->numeric()
                                ->minValue(0),
                            TextInput::make('annual_revenue')
                                ->label('Annual Revenue ($)')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('$'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('bonding_capacity')
                                ->label('Bonding Capacity ($)')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('$'),
                            TextInput::make('insurance_carrier')
                                ->label('Insurance Carrier')
                                ->maxLength(255),
                        ]),
                        TextInput::make('coverage')
                            ->label('Coverage Amount')
                            ->maxLength(255),
                    ])->columnSpanFull(),

                Section::make('Safety Information')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('emr')
                                ->label('EMR (Experience Modification Rate)')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0),
                            TextInput::make('trir')
                                ->label('TRIR (Total Recordable Incident Rate)')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0),
                            TextInput::make('safety_contact')
                                ->label('Safety Contact')
                                ->maxLength(255),
                        ]),
                    ])->columnSpanFull(),

                Section::make('Address')
                    ->schema([
                        Textarea::make('address')
                            ->label('Street Address')
                            ->rows(2)
                            ->columnSpanFull(),
                        Grid::make(3)->schema([
                            TextInput::make('city')
                                ->label('City')
                                ->maxLength(255),
                            TextInput::make('state')
                                ->label('State')
                                ->maxLength(255),
                            TextInput::make('zip')
                                ->label('ZIP Code')
                                ->maxLength(255),
                        ]),
                    ])->columnSpanFull(),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('documents')
                            ->label('Supporting Documents')
                            ->multiple()
                            ->disk('public')
                            ->directory('prequalifications')
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240) // 10MB
                            ->helperText('Upload supporting documents (PDF, Word, Images). Max 10MB per file.')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }
}
