<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('categories.name')
                    ->label('Supplier')
                    ->sortable()
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                TextColumn::make('colors.name')
                    ->label('Colors')
                    ->sortable()
                    ->badge()
                    ->separator(',')
                    ->toggleable(),
                TextColumn::make('available_units')
                    ->label('Stock')
                    ->getStateUsing(
                        fn($record) =>
                        $record->units()->where('is_sold', false)->count()
                    ),

                TextColumn::make('purchase_price')
                    ->money('INR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('selling_price')
                    ->money('INR')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
