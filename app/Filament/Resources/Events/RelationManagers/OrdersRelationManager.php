<?php

namespace App\Filament\Resources\Events\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ExportAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_code')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_code')
            ->columns([
                TextColumn::make('order_code')
                    ->label('Kode Order')
                    ->searchable(),

                TextColumn::make('customer_name')
                    ->label('Nama Pembeli')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status Pembayaran')
                    ->badge() // <-- Ini mengubah teks menjadi badge
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed', 'expired' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('quantity')
                    ->label('Jml Tiket')
                    ->numeric(),

                TextColumn::make('total_price')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
                // --- TAMBAHKAN TOMBOL EKSPOR DI SINI ---
                ExportAction::make()
                    ->label('Ekspor ke Excel')
                    ->color('success'),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    // Bulk actions are typically disabled, but the group is needed.
                    BulkActionGroup::make([]),
                ]),
            ]);
    }
}
