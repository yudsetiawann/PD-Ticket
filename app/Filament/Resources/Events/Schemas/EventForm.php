<?php

namespace App\Filament\Resources\Events\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

// --- SEMUA 'use' STATEMENT DI BAWAH INI TELAH DIPERBAIKI ---
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // Grup untuk kolom utama (2/3 dari lebar)
            Group::make()
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Textarea::make('description')
                        ->label('Deskripsi Lengkap Event')
                        ->rows(8)
                        ->columnSpanFull(),

                    SpatieMediaLibraryFileUpload::make('thumbnail')
                        ->collection('thumbnails')
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->columnSpanFull(),

                    SpatieMediaLibraryFileUpload::make('gallery')
                        ->collection('gallery')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->imageEditor()
                        ->columnSpanFull(),
                ])
                ->columnSpan(2),

            // Grup untuk kolom samping (1/3 dari lebar)
            Group::make()
                ->schema([
                    TextInput::make('location')->required(),

                    TextInput::make('location_map_link')
                        ->label('Link Embed Google Maps')
                        ->placeholder('Contoh: https://www.google.com/maps/embed?pb=...')
                        ->url(),

                    DatePicker::make('starts_at')
                        ->required()
                        ->label('Tanggal Mulai')
                        ->native(false),

                    DatePicker::make('ends_at')
                        ->label('Tanggal Selesai')
                        ->after('starts_at')
                        ->native(false),

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

                    TextInput::make('contact_person_name')
                        ->label('Nama Narahubung'),

                    TextInput::make('contact_person_phone')
                        ->label('No. Telepon Narahubung')
                        ->tel(),

                    Hidden::make('user_id')->default(fn() => Auth::id()),
                ])
                ->columnSpan(1),
        ])->columns(3);
    }
}
