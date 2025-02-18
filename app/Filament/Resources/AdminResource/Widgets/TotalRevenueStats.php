<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalRevenueStats extends BaseWidget
{

    // Stats Overview widgets is that they are refreshed automatically every 5 seconds.
    // This is performed with Livewire wire:poll feature,
    // protected static ?string $pollingInterval = '60s';

    // it can customize it or turn it off like this:
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Revenue Today (USD)',
                number_format(Order::whereDate('created_at', date('Y-m-d'))->sum('price') / 100, 2)
            ),
            Stat::make(
                'Revenue Last 7 Days (USD)',
                number_format(Order::where('created_at', '>=', now()->subDays(7)->startOfDay())->sum('price') / 100, 2)
            ),
            Stat::make(
                'Revenue Last 30 Days (USD)',
                number_format(Order::where('created_at', '>=', now()->subDays(30)->startOfDay())->sum('price') / 100, 2)
            )
        ];
    }
}
