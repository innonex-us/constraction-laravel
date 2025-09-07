<?php

namespace App\Filament\Widgets;

use App\Models\{Service, Project, Post, GalleryItem, ContactMessage, Prequalification};
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    // Match parent trait (non-static) for polling interval
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $services = Service::count();
        $activeServices = Service::where('is_active', true)->count();

        $projects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();

        $posts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();

        $gallery = GalleryItem::where('is_published', true)->count();

        $inboxOpen = ContactMessage::whereNull('resolved_at')->count();
        $prequals = Prequalification::count();

        return [
            Stat::make('Services', $services)
                ->description($activeServices . ' active')
                ->descriptionIcon('heroicon-o-bolt', IconPosition::Before)
                ->color('success')
                ->icon('heroicon-o-wrench-screwdriver'),

            Stat::make('Projects', $projects)
                ->description($completedProjects . ' completed')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before)
                ->color('info')
                ->icon('heroicon-o-briefcase'),

            Stat::make('Posts', $posts)
                ->description($publishedPosts . ' published')
                ->descriptionIcon('heroicon-o-newspaper', IconPosition::Before)
                ->color('warning')
                ->icon('heroicon-o-document-text'),

            Stat::make('Gallery Items', $gallery)
                ->description('Optimized & published')
                ->descriptionIcon('heroicon-o-photo', IconPosition::Before)
                ->color('success')
                ->icon('heroicon-o-photo'),

            Stat::make('Open Messages', $inboxOpen)
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-o-inbox', IconPosition::Before)
                ->color('danger')
                ->icon('heroicon-o-inbox'),

            Stat::make('Prequalifications', $prequals)
                ->description('Trade partners')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
                ->color('info')
                ->icon('heroicon-o-user-group'),
        ];
    }
}
