<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Sales Overview';

    public ?string $filter = 'day';

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Daily',
            'week' => 'Weekly',
            'month' => 'Monthly',
            'year' => 'Yearly',
        ];
    }

    protected function getData(): array
    {
        $query = Order::query();

        switch ($this->filter) {
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;

            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;

            case 'year':
                $query->whereYear('created_at', now()->year);
                break;

            default:
                $query->whereDate('created_at', today());
        }

        $sales = $query
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => $sales->values(),
                ],
            ],
            'labels' => $sales->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
