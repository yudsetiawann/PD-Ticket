<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class MyTickets extends Component
{
    public $orders;

    public function mount()
    {
        $this->orders = Order::where('user_id', Auth::id())
            ->with('event') // Load relasi event untuk efisiensi
            ->latest()
            ->get();
    }

    public function cancelOrder(int $orderId)
    {
        // Cari order berdasarkan ID
        $order = Order::find($orderId);

        // Lakukan beberapa pengecekan keamanan dan validasi
        if (!$order || $order->user_id !== Auth::id() || $order->status !== 'pending') {
            // Jika order tidak ada, bukan milik user, atau statusnya bukan pending, batalkan aksi.
            return;
        }

        // Hapus order dari database
        $order->delete();

        // Muat ulang data order agar tampilan terupdate
        $this->mount();

        // Kirim pesan sukses (opsional)
        session()->flash('success', 'Pesanan berhasil dibatalkan.');
    }

    public function downloadTicket(int $orderId)
    {
        $order = Order::find($orderId);

        // Pastikan user hanya bisa download tiket miliknya
        if ($order && $order->user_id === Auth::id()) {
            $ticket = $order->getFirstMedia('etickets');
            if ($ticket) {
                return $ticket; // Livewire akan otomatis men-trigger download
            }
        }
    }

    public function render()
    {
        return view('livewire.my-tickets');
    }
}
