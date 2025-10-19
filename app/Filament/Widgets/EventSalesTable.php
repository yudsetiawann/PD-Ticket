<?php

// namespace App\Filament\Widgets;

// use App\Models\Event;
// use Filament\Tables;
// use Filament\Tables\Table;
// use Filament\Widgets\TableWidget as BaseWidget;
// use Filament\Tables\Columns\TextColumn;

// class EventSalesTable extends BaseWidget
// {
//     protected static bool $isLazy = true;
//     protected static ?string $heading = 'Tiket Terjual per Event';
//     protected int | string | array $columnSpan = 'full';

//     public function table(Table $table): Table
//     {
//         return $table
//             ->query(
//                 Event::query()
//             )
//             ->columns([
//                 TextColumn::make('title')
//                     ->label('Nama Event')
//                     ->sortable(),

//                 TextColumn::make('ticket_sold')
//                     ->label('Terjual')
//                     ->numeric()
//                     ->sortable(),

//                 TextColumn::make('ticket_quota')
//                     ->label('Sisa Kuota')
//                     ->numeric()
//                     ->sortable(),
//             ])
//             ->defaultSort('ticket_sold', 'desc');
//     }
// }
