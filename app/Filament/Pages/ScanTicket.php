<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log; // Tambahkan Log

class ScanTicket extends Page
{
    // Properti ini sudah benar, tidak perlu diubah
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected string $view = 'filament.pages.scan-ticket';
    protected static ?string $navigationLabel = 'Scan E-Ticket';
    protected static ?string $title = 'Check-in Scanner';

    public ?Order $lastScannedOrder = null;
    public ?string $scanResult = null;
    public ?string $resultColor = null;
    public string $manualCode = '';

    /**
     * Listener untuk hasil scan dari kamera.
     * Hanya menerima data dan meneruskannya ke metode validasi utama.
     */
    #[On('qr-code-scanned')]
    public function handleScannedCode(string $code)
    {
        Log::info('QR Code Scanned:', ['code' => $code]); // Tambahkan log
        $this->validateTicketCode($code);
    }

    /**
     * Metode untuk memproses kode yang diketik manual
     */
    public function processManualCode()
    {
        Log::info('Manual Code Submitted:', ['code' => $this->manualCode]);

        if (!str_starts_with($this->manualCode, 'TIX-')) {
            $this->scanResult = 'ERROR: Format kode tiket tidak valid. Harus diawali TIX-.';
            $this->resultColor = 'danger';
            $this->lastScannedOrder = null;

            // Hentikan juga scanner jika ada error format
            $this->dispatch('stop-scanner'); // <-- TAMBAHKAN INI
            return;
        }

        $this->validateTicketCode($this->manualCode);

        // Setelah validasi, jika ada hasilnya, hentikan scanner
        if ($this->scanResult) {
            $this->dispatch('stop-scanner'); // <-- TAMBAHKAN INI
        }
    }

    /**
     * Metode validasi tiket utama (private, dipanggil oleh handleScannedCode dan processManualCode)
     */
    private function validateTicketCode(string $code)
    {
        $order = Order::with('event:id,title')->where('ticket_code', $code)->first(); // Optimasi query event

        if (!$order) {
            $this->scanResult = 'GAGAL: Tiket tidak valid atau tidak ditemukan.';
            $this->resultColor = 'danger';
            $this->lastScannedOrder = null;
            Log::warning('Scan Gagal: Tiket tidak ditemukan', ['code' => $code]); // Tambahkan log
            return;
        }

        // Muat relasi user hanya jika diperlukan (untuk optimasi)
        $order->load('user:id,name');

        if ($order->checked_in_at) {
            $this->scanResult = 'DUPLIKAT: Tiket ini sudah di-scan pada ' . $order->checked_in_at->format('d M Y, H:i:s');
            $this->resultColor = 'danger';
            $this->lastScannedOrder = $order;
            Log::warning('Scan Gagal: Tiket duplikat', ['code' => $code, 'order' => $order->id]); // Tambahkan log
            return;
        }

        if ($order->status !== 'paid') {
            $this->scanResult = 'PENDING: Pembayaran untuk tiket ini belum lunas.';
            $this->resultColor = 'warning';
            $this->lastScannedOrder = $order;
            Log::warning('Scan Gagal: Pembayaran pending', ['code' => $code, 'order' => $order->id]); // Tambahkan log
            return;
        }

        // Jika validasi lolos
        try {
            $order->update(['checked_in_at' => now()]);
            $this->scanResult = 'BERHASIL: Check-in sukses!';
            $this->resultColor = 'success';
            $this->lastScannedOrder = $order;
            Log::info('Scan Berhasil: Check-in sukses', ['code' => $code, 'order' => $order->id]); // Tambahkan log
        } catch (\Exception $e) {
            $this->scanResult = 'ERROR SISTEM: Gagal menyimpan data check-in.';
            $this->resultColor = 'danger';
            $this->lastScannedOrder = $order; // Tampilkan data order meskipun gagal update
            Log::error('Scan Error: Gagal update check_in_at', ['code' => $code, 'order' => $order->id, 'error' => $e->getMessage()]); // Tambahkan log
        }
    }

    /**
     * Metode untuk mereset tampilan dan memulai scan baru
     */
    public function resetScan()
    {
        $this->scanResult = null;
        $this->lastScannedOrder = null;
        $this->manualCode = '';
        $this->dispatch('start-scanner');
        Log::info('Scanner reset initiated.'); // Tambahkan log
    }
}
