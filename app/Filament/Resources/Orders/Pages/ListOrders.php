<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            \Filament\Actions\Action::make('newSale')
                ->label('New Order')
                ->url('/admin/sales')
                ->icon('heroicon-o-plus')
                ->button(),
        ];
    }
}
