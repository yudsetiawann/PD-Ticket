<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SalesStatsOverview extends BaseWidget
{
    protected static bool $isLazy = true; // <-- TAMBAHKAN BARIS INI
    protected function getStats(): array
    {
        // Ambil semua order yang sudah lunas
        $paidOrders = Order::where('status', 'paid');

        // Hitung total pendapatan
        $totalRevenue = $paidOrders->sum('total_price');

        // Hitung total tiket terjual (jumlah peserta)
        $totalTicketsSold = $paidOrders->sum('quantity');

        return [
            Stat::make('Total Pendapatan', Number::currency($totalRevenue, 'IDR'))
                ->description('Dari semua transaksi yang berhasil')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Tiket Terjual', $totalTicketsSold)
                ->description('Jumlah total peserta terdaftar')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),
        ];
    }
}
