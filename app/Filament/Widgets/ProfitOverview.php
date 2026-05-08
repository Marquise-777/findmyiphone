<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Laravel\Prompts\Grid;

class ProfitOverview extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.profit-overview';

    public ?array $dateRange = [];

    protected function getFormSchema(): array
    {
        return [
            ComponentsGrid::make(2)
            ->schema([
                DatePicker::make('dateRange.start')
                    ->label('From')
                    ->default(now()->startOfMonth()),
                DatePicker::make('dateRange.end')
                    ->label('To')
                    ->default(now()->endOfMonth()),
            ]),
        ];
    }

    public function getProfit(): float
    {
        $start = $this->dateRange['start'] ?? now()->startOfMonth();
        $end = $this->dateRange['end'] ?? now()->endOfMonth();

        $totalRevenue = DB::table('orders')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');

        $totalCost = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->sum('order_items.cost_price');

        return (float) ($totalRevenue - $totalCost);
    }

    protected function getViewData(): array
    {
        return [
            'profit' => $this->getProfit(),
            'start' => $this->dateRange['start'] ?? now()->startOfMonth(),
            'end' => $this->dateRange['end'] ?? now()->endOfMonth(),
        ];
    }
}
