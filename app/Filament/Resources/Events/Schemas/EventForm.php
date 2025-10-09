<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()->schema([
                TextInput::make('title')->required(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('location'),
                TextInput::make('ticket_price')
                    ->label('Harga Tiket')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                TextInput::make('ticket_quota')
                    ->label('Kuota Tiket')
                    ->numeric()
                    ->required(),
                TextInput::make('ticket_sold')
                    ->label('Tiket Terjual')
                    ->disabled()
                    ->dehydrated(false),
                DatePicker::make('starts_at')
                    ->required()
                    ->label('Tanggal Mulai')
                    ->native(false), // gunakan widget Filament, bukan input bawaan browser
                DatePicker::make('ends_at')
                    ->label('Tanggal Selesai')
                    ->after('starts_at')
                    ->native(false),
                FileUpload::make('thumbnail')
                    ->image()
                    ->directory('thumbnails')
                    ->maxSize(2048)
                    ->imagePreviewHeight('200')
                    ->disk('public')   // penting: simpan ke storage/app/public
                    ->required(),
                Hidden::make('user_id')
                    ->default(fn() => Auth::id()),
            ])
        ]);
    }
}
