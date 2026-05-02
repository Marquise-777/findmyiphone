<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\ProductUnit;
use App\Models\Order;
use App\Models\OrderItem;
use BackedEnum;
use UnitEnum;

class Sales extends Page implements \Filament\Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string|UnitEnum|null $navigationGroup = 'Sales';
    protected string $view = 'filament.pages.sales';

    // Add navigation group and sort
    // protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 0;  // shows before Orders (which has sort = 1)

    public array $cart = [];
    public string $imei_input = '';

    public $customer_id;
    public $discountPercent = 0;

    public $paymentMethod = 'cash';
    public $paidAmount = 0;

    public function form($form)
    {
        return $form->schema([

            Forms\Components\Select::make('customer_id')
                ->label('Customer')
                ->options(\App\Models\Customer::pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('phone'),
                    Forms\Components\TextInput::make('email'),
                ])
                ->createOptionUsing(function (array $data) {
                    return \App\Models\Customer::create($data)->id;
                }),
        ]);
    }

    public function scanImei()
    {
        $imei = trim($this->imei_input);
        if (!$imei) return;

        $this->addToCart($imei);
        $this->imei_input = '';

        // Re-focus the IMEI input field
        $this->dispatch('focus-imei');
    }

    public function addToCart($imei)
    {
        $unit = \App\Models\ProductUnit::where('imei', $imei)
            ->where('is_sold', false)
            ->first();

        if (!$unit) {
            \Filament\Notifications\Notification::make()
                ->title("Invalid or already sold IMEI")
                ->danger()
                ->send();
            return;
        }

        //  Prevent duplicate in cart
        if (collect($this->cart)->contains('unit_id', $unit->id)) {
            \Filament\Notifications\Notification::make()
                ->title("Already scanned")
                ->warning()
                ->send();
            return;
        }

        $this->cart[] = [
            'unit_id' => $unit->id,
            'product_name' => $unit->product->name,
            'price' => $unit->product->selling_price,
        ];
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        $subtotal = collect($this->cart)->sum('price');
        $discountPercent = (float) ($this->discountPercent ?? 0);
        $discountPercent = min(100, max(0, $discountPercent));
        $discountAmount = $subtotal * ($discountPercent / 100);
        $total = $subtotal - $discountAmount;

        // Validate paid amount
        $paid = (float) $this->paidAmount;
        if ($paid < 0) $paid = 0;
        $due = max(0, $total - $paid); // amount still owed (if paid less than total)
        // If you want to allow overpayment (change), set due to negative: $due = $total - $paid;

        $order = Order::create([
            'invoice_number' => 'INV-' . now()->timestamp,
            'customer_id' => !empty($this->customer_id) ? $this->customer_id : null,
            'subtotal' => $subtotal,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'tax' => 0,
            'paid' => $paid,
            'due' => $due,
            'total' => $total,
            'payment_method' => $this->paymentMethod,
        ]);

        foreach ($this->cart as $item) {
            $unit = ProductUnit::find($item['unit_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $unit->product_id,
                'product_unit_id' => $unit->id,
                'price' => $unit->product->selling_price,
            ]);
            $unit->update(['is_sold' => true, 'sold_at' => now()]);
        }

        // Reset
        $this->cart = [];
        $this->discountPercent = 0;
        $this->customer_id = null;
        $this->paidAmount = 0;
        $this->paymentMethod = 'cash';

        Notification::make()->title('Sale completed')->success()->send();
        return redirect()->to('/admin/invoice/' . $order->id);
    }

    // Add this method inside the Sales class
    public function removeFromCart($index)
    {
        if (isset($this->cart[$index])) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart); // re-index
        }
    }
}
