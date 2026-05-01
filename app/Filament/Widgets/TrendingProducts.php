<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;

class TrendingProducts extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Trending Products';

    protected function getData(): array
    {
        $data = OrderItem::selectRaw('product_id, COUNT(*) as total_quantity')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Units Sold',
                    'data' => $data->pluck('total_quantity'),
                ],
            ],
            'labels' => $data->map(fn($item) => $item->product?->name ?? 'N/A'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
