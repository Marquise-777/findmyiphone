<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->date('d M y'), // 11 Feb 26 format
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('discount')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('due')
                    ->searchable()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('paid')
                    ->searchable()
                    ->sortable()
                    ->color('success'),
                TextColumn::make('total')
                    ->searchable()
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
