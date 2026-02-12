<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $dailySales = Order::query()
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->limit(7)
            ->pluck('total')
            ->toArray();
        return [
            Stat::make('Total Sales', number_format(Order::sum('total'), 2))
                ->description('All time sales')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($dailySales),

            Stat::make('Total Due', number_format(Order::sum('due'), 2))
                ->description('Outstanding balance')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Total Orders', Order::count())
                ->description('Orders created')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
        ];
    }
}
