<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Supplier;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    protected static ?string $recordTitleAttribute = 'imei';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('imei')
                ->label('IMEI/Serial')
                ->required()
                ->unique(ignoreRecord: true),
            Select::make('supplier_id')
                ->label('Supplier')
                ->relationship('supplier', 'name')
                ->searchable()
                ->createOptionForm([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->maxLength(255),
                ])
                ->nullable()
                ->preload(),
            TextInput::make('cost_price')
                ->label('Cost Price')
                ->numeric()
                ->prefix('₹')
                ->nullable()
                ->visible(fn() => Auth::user()?->id === 1),
            Toggle::make('is_sold')
                ->disabled(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('imei')->searchable()
                    ->label('IMEI/Serial'),

                Tables\Columns\IconColumn::make('is_sold')
                    ->boolean(),

                TextColumn::make('supplier.name')->label('Supplier'),
                TextColumn::make('cost_price')
                    ->label('Cost Price')
                    ->prefix('₹')
                    ->visible(fn() => Auth::user()?->id === 1),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->headerActions([
                CreateAction::make(),

                // Bulk add action
                Action::make('bulk_imei')
                    ->label('Bulk Add IMEI/Serial')
                    ->form([
                        Textarea::make('imeis')
                            ->label('Paste IMEI/Serial (one per line)')
                            ->rows(10)
                            ->required(),

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->maxLength(255),
                            ])    
                            ->nullable(),

                        TextInput::make('cost_price')
                            ->label('Cost Price')
                            ->numeric()
                            ->prefix('₱')
                            ->nullable()
                            ->visible(fn() => Auth::user()?->id === 1), // admin only
                    ])
                    ->action(function (array $data, $livewire) {
                        $product = $livewire->ownerRecord;

                        $imeis = preg_split('/\r\n|\r|\n/', $data['imeis']);

                        $created = 0;
                        foreach ($imeis as $imei) {
                            $imei = trim($imei);
                            if ($imei === '') continue;

                            // Use updateOrCreate to avoid duplicates
                            \App\Models\ProductUnit::updateOrCreate(
                                ['imei' => $imei],
                                [
                                    'product_id' => $product->id,
                                    'supplier_id' => $data['supplier_id'] ?? null,
                                    'cost_price' => $data['cost_price'] ?? null,
                                ]
                            );
                            $created++;
                        }

                        Notification::make()
                            ->title($created . ' IMEI(s)/Serial(s) added successfully')
                            ->success()
                            ->send();

                        // Refresh the table
                        // $livewire->mountTableAction($livewire->getCachedTableAction('bulk_imei'));
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
