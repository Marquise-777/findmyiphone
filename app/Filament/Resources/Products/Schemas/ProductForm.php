<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sku')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->readOnly()
                    ->dehydrated()
                    ->reactive(),


                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $brand = \App\Models\Brand::create($data);
                        return $brand->id;
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($state, \Filament\Schemas\Components\Utilities\Set $set) {

                        if (!$state) {
                            return;
                        }

                        $brand = \App\Models\Brand::find($state);

                        if (!$brand) {
                            return;
                        }

                        $prefix = strtoupper(substr($brand->name, 0, 3));

                        $lastProduct = \App\Models\Product::where('sku', 'like', $prefix . '-%')
                            ->latest('id')
                            ->first();

                        $nextNumber = 1;

                        if ($lastProduct && preg_match('/-(\d+)$/', $lastProduct->sku, $matches)) {
                            $nextNumber = ((int) $matches[1]) + 1;
                        }

                        $sku = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                        $set('sku', $sku);
                    }),


                Select::make('categories')
                    ->label('Supplier')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return \App\Models\Category::create($data)->id;
                    }),


                Select::make('colors')
                    ->relationship('colors', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return \App\Models\Color::create($data)->id;
                    }),


                TextInput::make('available_stock')
                    ->label('Stock')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn($record) => $record !== null),

                TextInput::make('selling_price')
                    ->numeric(),


                Toggle::make('is_active')
                    ->default(true),
            ])
            ->columns(2);
    }
}
