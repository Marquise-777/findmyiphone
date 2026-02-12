<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('print')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('orders.print', $this->record))
                ->openUrlInNewTab(),
        ];
    }
    protected function beforeSave(): void
    {
        // Restore old stock first
        foreach ($this->record->items as $item) {
            $product = $item->product;

            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }
    }

    protected function afterSave(): void
    {
        // Deduct new stock
        foreach ($this->record->items as $item) {
            $product = $item->product;

            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}
