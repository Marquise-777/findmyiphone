<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;

class Invoice extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.invoice';

    public $order;

    public function mount($orderId)
    {
        $this->order = Order::with(['items.unit.product', 'customer'])
            ->findOrFail($orderId);
    }
}
