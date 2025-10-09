<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // <-- Jangan lupa import model Order

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran untuk order yang spesifik.
     */
    public function show(Order $order)
    {
        // Fitur keamanan untuk memastikan pengguna hanya bisa melihat order miliknya.
        if ($order->user_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE ORDER INI.');
        }

        // Kirim data order ke view 'payment.blade.php'
        return view('orders.payment', compact('order'));
    }
}
