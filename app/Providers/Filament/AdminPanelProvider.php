<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Support\Enums\Width;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\RecentMessages;
use App\Filament\Widgets\ContentActivityChart;
use App\Filament\Widgets\TradesBreakdown;
use App\Filament\Widgets\RecentPosts;
use App\Filament\Widgets\RecentProjects;
use App\Filament\Widgets\RecentPrequalifications;
use App\Models\SiteSetting;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->brandName(fn () => optional(SiteSetting::first())->site_name ?? config('app.name'))
            ->brandLogo(fn () => ($p = optional(SiteSetting::first())->logo_path) ? asset($p) : null)
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop(true)
            ->sidebarFullyCollapsibleOnDesktop(true);

        // Use Vite theme only when dev server is running or a build exists.
        $themeEntry = 'resources/css/filament/admin/theme.css';
        $manifestPath = public_path('build/manifest.json');
        $hasViteTheme = file_exists($manifestPath) && str_contains((string) @file_get_contents($manifestPath), $themeEntry);
        if ($hasViteTheme) {
            $panel->viteTheme('resources/css/filament/admin/theme.css');
        }

        return $panel
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                DashboardStats::class,
                ContentActivityChart::class,
                TradesBreakdown::class,
                RecentMessages::class,
                RecentPosts::class,
                RecentProjects::class,
                RecentPrequalifications::class,
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
