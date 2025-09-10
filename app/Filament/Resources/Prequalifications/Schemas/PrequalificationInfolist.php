<?php

namespace App\Filament\Resources\Prequalifications\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PrequalificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company Information')
                    ->schema([
                        TextEntry::make('company_name')
                            ->label('Company Name'),
                        TextEntry::make('contact_name')
                            ->label('Contact Name'),
                        TextEntry::make('trade')
                            ->label('Primary Trade'),
                        TextEntry::make('email')
                            ->label('Email Address'),
                        TextEntry::make('phone')
                            ->label('Phone Number'),
                        TextEntry::make('website')
                            ->label('Website')
                            ->url(fn ($record) => $record->website),
                    ])->columns(2),

                Section::make('Business Details')
                    ->schema([
                        TextEntry::make('license_number')
                            ->label('License Number'),
                        TextEntry::make('years_in_business')
                            ->label('Years in Business')
                            ->suffix(' years'),
                        TextEntry::make('annual_revenue')
                            ->label('Annual Revenue')
                            ->money('USD'),
                        TextEntry::make('bonding_capacity')
                            ->label('Bonding Capacity')
                            ->money('USD'),
                        TextEntry::make('insurance_carrier')
                            ->label('Insurance Carrier'),
                        TextEntry::make('coverage')
                            ->label('Coverage Amount'),
                    ])->columns(3),

                Section::make('Safety Information')
                    ->schema([
                        TextEntry::make('emr')
                            ->label('EMR (Experience Modification Rate)')
                            ->numeric(decimalPlaces: 2),
                        TextEntry::make('trir')
                            ->label('TRIR (Total Recordable Incident Rate)')
                            ->numeric(decimalPlaces: 2),
                        TextEntry::make('safety_contact')
                            ->label('Safety Contact'),
                    ])->columns(3),

                Section::make('Address')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Street Address'),
                        TextEntry::make('city')
                            ->label('City'),
                        TextEntry::make('state')
                            ->label('State'),
                        TextEntry::make('zip')
                            ->label('ZIP Code'),
                    ])->columns(3),

                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                        TextEntry::make('documents')
                            ->label('Documents')
                            ->badge()
                            ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . ' file(s)' : 'No documents')
                            ->color(fn ($state) => is_array($state) && count($state) > 0 ? 'success' : 'gray'),
                        TextEntry::make('created_at')
                            ->label('Submitted At')
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }
}
