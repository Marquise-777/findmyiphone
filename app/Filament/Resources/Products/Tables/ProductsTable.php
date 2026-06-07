<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Supplier;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(
                        query: function ($query, string $search) {
                            $query->where(function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('sku', 'like', "%{$search}%")
                                    ->orWhereHas('units', function ($unitQuery) use ($search) {
                                        $unitQuery->where('imei', 'like', "%{$search}%");
                                    });
                            });
                        }
                    )
                    ->sortable(),

                TextColumn::make('supplier_names')
                    ->label('Supplier')
                    ->getStateUsing(function ($record) {
                        return $record->units()
                            ->with('supplier')
                            ->get()
                            ->pluck('supplier.name')
                            ->filter()
                            ->unique()
                            ->join(', ');
                    })
                    ->wrap(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('categories.name')
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
                SelectFilter::make('supplier')
                    ->label('Supplier')
                    ->options(
                        Supplier::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->query(function ($query, array $data) {
                        if (blank($data['value'])) {
                            return $query;
                        }

                        return $query->whereHas('units', function ($q) use ($data) {
                            $q->where('supplier_id', $data['value']);
                        });
                    }),
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
