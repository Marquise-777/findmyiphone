<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProfitTrend extends ChartWidget
{
    public function getHeading(): string
    {
        return 'Profit Trend';
    }

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'monthly';

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'yearly' => 'Yearly',
        ];
    }

    protected function getData(): array
    {
        return match ($this->filter) {
            'daily' => $this->daily(),
            'weekly' => $this->weekly(),
            'yearly' => $this->yearly(),
            default => $this->monthly(),
        };
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function daily(): array
    {
        $rows = OrderItem::query()
            ->selectRaw("
                DATE(created_at) as label,
                SUM(price - cost_price) as profit
            ")
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Profit',
                    'data' => $rows->pluck('profit'),
                ],
            ],
            'labels' => $rows->pluck('label'),
        ];
    }

    private function weekly(): array
    {
        $rows = OrderItem::query()
            ->selectRaw("
                YEARWEEK(created_at,1) as label,
                SUM(price - cost_price) as profit
            ")
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Profit',
                    'data' => $rows->pluck('profit'),
                ],
            ],
            'labels' => $rows->pluck('label'),
        ];
    }

    private function monthly(): array
    {
        $rows = OrderItem::query()
            ->selectRaw("
                DATE_FORMAT(created_at,'%b %Y') as label,
                SUM(price - cost_price) as profit,
                MIN(created_at) as sort_date
            ")
            ->groupBy('label')
            ->orderBy('sort_date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Profit',
                    'data' => $rows->pluck('profit'),
                ],
            ],
            'labels' => $rows->pluck('label'),
        ];
    }

    private function yearly(): array
    {
        $rows = OrderItem::query()
            ->selectRaw("
                YEAR(created_at) as label,
                SUM(price - cost_price) as profit
            ")
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Profit',
                    'data' => $rows->pluck('profit'),
                ],
            ],
            'labels' => $rows->pluck('label'),
        ];
    }
}
