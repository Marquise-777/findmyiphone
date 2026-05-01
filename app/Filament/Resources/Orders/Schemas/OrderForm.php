<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class OrderForm
{
    protected static function calculate(Get $get, Set $set): void
    {
        // Get items and ensure it's an array
        $items = $get('items') ?? [];

        // Calculate subtotal from item totals
        $subtotal = 0;
        foreach ($items as $index => $item) {
            // Recalculate each item's total to ensure accuracy
            $quantity = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['price'] ?? 0);
            $itemTotal = $quantity * $price;

            // Update the item total in the form state
            $set("items.{$index}.total", $itemTotal);

            $subtotal += $itemTotal;
        }

        // Get payment values with proper defaults
        $discount = (float) ($get('discount') ?? 0);
        $tax = (float) ($get('tax') ?? 0);
        $paid = (float) ($get('paid') ?? 0);

        // Calculate total and due
        $total = $subtotal - $discount + $tax;
        $due = max(0, $total - $paid);

        // Set the calculated values
        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('total', number_format($total, 2, '.', ''));
        $set('due', number_format($due, 2, '.', ''));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_number')
                    ->readOnly()
                    ->dehydrated()
                    ->unique(ignoreRecord: true),

                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('phone'),
                        TextInput::make('email'),
                    ])
                    ->required(),

                Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculate($get, $set);
                            })
                            ->schema([
                                Select::make('product_id')
                                    ->relationship(
                                        name: 'product',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn($query) => $query->with('colors')
                                    )
                                    ->getOptionLabelFromRecordUsing(function (Product $record) {

                                        $colors = $record->colors->pluck('name')->join(', ');


                                        return "{$record->name}" . ($colors ? " | Colors: {$colors}" : '');
                                        ($colors ? " | Colors: {$colors}" : '');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $product = Product::find($state);

                                            if ($product) {
                                                $set('price', $product->selling_price);
                                                $set('quantity', 0);
                                            }
                                        }

                                        self::calculate($get, $set);
                                    }),


                                TextInput::make('price')
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::calculate($get, $set);
                                    }),

                                Select::make('product_unit_id')
                                    ->label('IMEI / Serial')
                                    ->relationship('unit', 'imei')
                                    ->searchable()
                                    ->preload()
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->imei}")
                                    ->required(),

                                TextInput::make('total')
                                    ->readOnly()
                                    ->dehydrated()
                                    ->numeric()
                                    ->step(0.01),
                            ])
                            ->columns(2)
                    ]),

                Section::make('Payment')
                    ->schema([
                        TextInput::make('discount_percent')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculate($get, $set);
                            })
                            ->step(0.01)
                            ->minValue(0),

                        TextInput::make('tax')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculate($get, $set);
                            })
                            ->step(0.01)
                            ->minValue(0),

                        TextInput::make('paid')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculate($get, $set);
                            })
                            ->step(0.01)
                            ->minValue(0),

                        TextInput::make('subtotal')
                            ->readOnly()
                            ->dehydrated()
                            ->numeric()
                            ->step(0.01)
                            ->prefix('₹'),

                        TextInput::make('total')
                            ->readOnly()
                            ->dehydrated()
                            ->numeric()
                            ->step(0.01)
                            ->prefix('₹'),

                        TextInput::make('due')
                            ->readOnly()
                            ->dehydrated()
                            ->numeric()
                            ->step(0.01)
                            ->prefix('₹'),
                    ])
                    ->columns(3)
            ]);
    }
}
