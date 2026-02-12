<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    protected function afterCreate(): void
    {
        foreach ($this->record->items as $item) {
            $product = $item->product;

            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }
    }

    protected function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');

        $lastOrder = Order::whereDate('created_at', today())
            ->latest('id')
            ->first();

        $nextNumber = 1;

        if ($lastOrder && preg_match('/-(\d+)$/', $lastOrder->invoice_number, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return 'INV-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['invoice_number'] = $this->generateInvoiceNumber();

        return $data;
    }
}
