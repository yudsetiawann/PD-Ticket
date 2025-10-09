<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function createTransaction(array $params)
    {
        try {
            // Kode ini akan mencoba membuat transaksi
            return Snap::createTransaction($params);
        } catch (Exception $e) {
            // Jika gagal, akan melempar Exception dengan pesan error yang lebih jelas
            throw new Exception("Midtrans Error: " . $e->getMessage());
        }
    }
}
