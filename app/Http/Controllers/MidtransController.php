<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        // Log notifikasi yang masuk untuk debugging
        Log::info('Midtrans notification received:', $request->all());

        // Ambil data JSON dari body request
        $payload = $request->all();

        // Ambil data yang diperlukan dari payload
        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $transactionStatus = $payload['transaction_status'];
        $signatureKeyFromMidtrans = $payload['signature_key'];

        // Dapatkan server key dari config
        $serverKey = config('midtrans.server_key');

        // Hitung signature key versi kita
        $ourSignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Log untuk perbandingan signature key
        Log::info('Signature Key Dihitung: ' . $ourSignatureKey);
        Log::info('Signature Key dari Midtrans: ' . $signatureKeyFromMidtrans);

        // Verifikasi signature key
        if ($signatureKeyFromMidtrans !== $ourSignatureKey) {
            Log::error('Signature key tidak valid untuk order_code: ' . $orderId);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari order di database
        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            Log::warning('Order tidak ditemukan untuk order_code: ' . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status order berdasarkan status transaksi
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            Log::info('Mengupdate status menjadi "paid" untuk order_code: ' . $orderId);
            $order->update(['status' => 'paid']);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            Log::info('Mengupdate status menjadi "failed" untuk order_code: ' . $orderId);
            $order->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'Notification handled successfully'], 200);
    }
}
