<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class PaymentPage extends Component
{
    public Order $order;
    public ?string $paymentStatus = null;

    public function mount(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE ORDER INI.');
        }
        $this->order = $order;
    }

    // Method processPayment() sudah dihapus karena tidak lagi dipakai.

    #[On('payment-result')]
    public function handlePaymentResult(string $status, array $result)
    {
        // Method ini hanya untuk update UI, bukan update database
        $this->paymentStatus = $status;
    }

    public function render()
    {
        return view('livewire.payment-page');
    }
}
