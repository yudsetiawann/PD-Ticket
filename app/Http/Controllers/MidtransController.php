<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\SendETicket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        Log::info('Midtrans notification received:', $request->all());

        $payload = $request->all();

        // Verifikasi Signature Key (tidak ada perubahan di sini)
        $orderId = $payload['order_id'];
        $serverKey = config('midtrans.server_key');
        $ourSignatureKey = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);

        if ($payload['signature_key'] !== $ourSignatureKey) {
            Log::error('Signature key tidak valid untuk order_code: ' . $orderId);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            Log::warning('Order tidak ditemukan untuk order_code: ' . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Hanya proses notifikasi jika status order masih 'pending'.
        if ($order->status === 'pending') {
            if ($payload['transaction_status'] == 'capture' || $payload['transaction_status'] == 'settlement') {

                // --- URUTAN LOGIKA YANG BENAR DAN EFISIEN ---

                // 1. BUAT KODE TIKET UNIK DAN UPDATE STATUS ORDER (DALAM SATU KALI PROSES)
                $ticketCode = 'TIX-' . strtoupper(Str::random(10));
                $order->update([
                    'status' => 'paid',
                    'ticket_code' => $ticketCode,
                ]);
                Log::info('Status order dan ticket_code berhasil diupdate untuk: ' . $orderId);

                // 2. UPDATE JUMLAH TIKET DI EVENT TERKAIT
                $event = $order->event;
                if ($event) {
                    $event->ticket_sold += $order->quantity;
                    $event->ticket_quota -= $order->quantity;
                    $event->save();
                    Log::info("Stok tiket untuk event '{$event->title}' berhasil diupdate.");
                }

                // 3. BUAT PDF E-TICKET DAN LAMPIRKAN KE ORDER
                $pdf = Pdf::loadView('pdf.eticket', compact('order'));
                $tempPdfPath = storage_path('app/temp/' . $order->ticket_code . '.pdf');
                $pdf->save($tempPdfPath);
                $order->addMedia($tempPdfPath)->toMediaCollection('etickets');
                Log::info('PDF E-Ticket berhasil dibuat dan dilampirkan untuk: ' . $orderId);

                // 4. KIRIM EMAIL KE PENGGUNA (DENGAN PENANGANAN ERROR)
                try {
                    Mail::to($order->user->email)->send(new SendETicket($order));
                    Log::info('Email e-ticket berhasil dikirim ke: ' . $order->user->email);
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email e-ticket untuk order ' . $orderId . ': ' . $e->getMessage());
                    // Proses tidak dihentikan, karena pembayaran sudah berhasil.
                }
            } elseif ($payload['transaction_status'] == 'deny' || $payload['transaction_status'] == 'expire' || $payload['transaction_status'] == 'cancel') {
                $order->update(['status' => 'failed']);
                Log::info('Status order diupdate menjadi "failed" untuk: ' . $orderId);
            }
        }

        return response()->json(['message' => 'Notification handled successfully'], 200);
    }
}
