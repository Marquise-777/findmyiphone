<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    protected static ?string $recordTitleAttribute = 'imei';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('imei')
                ->label('IMEI/Serial')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Toggle::make('is_sold')
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

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->headerActions([
    CreateAction::make(),

            \Filament\Actions\Action::make('bulk_imei')
                ->label('Bulk Add IMEI/Serial')
                ->form([
                    Textarea::make('imeis')
                        ->label('Paste IMEI/Serial (one per line)')
                        ->rows(10)
                        ->required(),
                ])
                ->action(function (array $data, $livewire) {
                    $product = $livewire->ownerRecord;

                    $imeis = preg_split('/\r\n|\r|\n/', $data['imeis']);

                    foreach ($imeis as $imei) {
                        $imei = trim($imei);

                        if ($imei !== '') {
                            \App\Models\ProductUnit::firstOrCreate([
                                'imei' => $imei,
                            ], [
                                'product_id' => $product->id,
                            ]);
                        }
                    }

                    Notification::make()
                        ->title('IMEIs/Serial added successfully')
                        ->success()
                        ->send();
                }),
        ])
        ->actions([
            DeleteAction::make(),
        ]);
}
}