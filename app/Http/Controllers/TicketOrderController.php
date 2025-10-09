<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade
use Illuminate\Support\Facades\Auth;
use Throwable; // <-- Import Throwable

class TicketOrderController extends Controller
{
    public function create(Event $event)
    {
        return view('orders.create', compact('event'));
    }

    public function store(Request $request, Event $event, MidtransService $midtrans)
    {
        // 1. Perbaikan pada aturan validasi 'max'
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . ($event->ticket_quota - $event->ticket_sold),
            // Anda bisa menambahkan validasi untuk data pelanggan di sini
            // 'customer_name' => 'required|string|min:3',
            // 'phone_number' => 'required|string|min:10',
            // ...etc
        ]);

        // 2. Menggunakan Database Transaction
        try {
            $order = DB::transaction(function () use ($request, $event, $midtrans) {
                $order = Order::create([
                    'event_id' => $event->id,
                    'user_id' => Auth::id(),
                    'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                    'quantity' => $request->quantity,
                    'total_price' => $event->ticket_price * $request->quantity,
                    'status' => 'pending',
                    // Jika Anda menambahkan field di form, simpan di sini
                    // 'customer_name' => $request->customer_name,
                    // 'phone_number' => $request->phone_number,
                ]);

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_code,
                        'gross_amount' => $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name, // atau $request->customer_name
                        'email' => Auth::user()->email,
                        'phone' => Auth::user()->phone, // atau $request->phone_number
                    ],
                ];

                $snap = $midtrans->createTransaction($params);

                $order->update([
                    'midtrans_token' => $snap->token,
                ]);

                // Kembalikan order agar bisa digunakan di luar transaction block
                return $order;
            });

            // Jika transaksi berhasil, redirect ke halaman pembayaran
            // Muat ulang relasi event agar bisa diakses di view
            $order->load('event');

            // Anda perlu membuat view baru `orders.payment` atau sesuaikan
            // dengan view `payment.blade.php` yang sudah ada
            return redirect()->route('orders.payment', $order);
        } catch (Throwable $e) {
            // Jika terjadi error, kembalikan ke halaman sebelumnya dengan pesan error
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage()]);
        }
    }
}
