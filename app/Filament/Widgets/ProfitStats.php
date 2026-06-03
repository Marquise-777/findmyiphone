<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProfitStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $profit = OrderItem::query()
            ->selectRaw('SUM(price - cost_price) as profit')
            ->value('profit') ?? 0;

        $todayProfit = OrderItem::query()
            ->whereDate('created_at', today())
            ->selectRaw('SUM(price - cost_price) as profit')
            ->value('profit') ?? 0;

        $monthProfit = OrderItem::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('SUM(price - cost_price) as profit')
            ->value('profit') ?? 0;

        return [
            Stat::make('Total Profit', '₹' . number_format($profit, 2))
                ->description('All time')
                ->color('success'),

            Stat::make('Today Profit', '₹' . number_format($todayProfit, 2))
                ->description(now()->format('d M Y'))
                ->color('info'),

            Stat::make('Monthly Profit', '₹' . number_format($monthProfit, 2))
                ->description(now()->format('F Y'))
                ->color('warning'),
        ];
    }
}
