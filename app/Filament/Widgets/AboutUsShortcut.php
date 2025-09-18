<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AboutUsShortcut extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $aboutPage = Page::where('slug', 'about')->first();
        
        return [
            Stat::make('About Us Page', 'Quick Edit')
                ->description('Edit your company\'s About Us page')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('success')
                ->url($aboutPage ? route('filament.admin.resources.pages.edit', $aboutPage->id) : route('filament.admin.resources.pages.create'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform',
                ]),
        ];
    }
}
