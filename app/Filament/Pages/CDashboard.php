<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TrendingProducts;
use Filament\Pages\Page;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class CDashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';   // overrides the default
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->id === 1;
    }
    // protected function getWidgets(): array
    // {
    //     return [
    //         StatsOverview::class,
    //         SalesChart::class,
    //         TrendingProducts::class,
    //     ];
    // }
}
