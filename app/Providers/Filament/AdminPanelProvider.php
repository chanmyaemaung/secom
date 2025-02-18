<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AdminResource\Widgets\LatestOrders;
use App\Filament\Resources\AdminResource\Widgets\OrdersPerDayChart;
use App\Filament\Resources\AdminResource\Widgets\Revenue30Days;
use App\Filament\Resources\AdminResource\Widgets\Revenue7Days;
use App\Filament\Resources\AdminResource\Widgets\RevenueToday;
use App\Filament\Resources\AdminResource\Widgets\TotalRevenueStats;
use App\Models\Order;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
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
        return $panel
            ->default()
            ->id('admin')
            ->path('panel')
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->login()
            // ->topNavigation()
            ->navigationGroups([
                'Classfiers',
                'General',
            ])
            ->globalSearchKeyBindings(['command+k', 'control+k'])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font('Noto Sans Myanmar')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                    // RevenueToday::class,
                    // Revenue7Days::class,
                    // Revenue30Days::class,
                OrdersPerDayChart::class,
                TotalRevenueStats::class,
                LatestOrders::class,
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
