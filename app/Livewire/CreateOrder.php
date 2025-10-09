<?php

namespace App\Livewire;

use Throwable;
use App\Models\Event;
use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class CreateOrder extends Component
{
    public Event $event;

    // Properti form (tidak perlu diubah)
    public string $fullName = '';
    public string $phoneNumber = '';
    public string $school = '';
    public string $level = '';
    public array $levels = ['Pemula', 'Dasar I', 'Dasar II', 'Cakel', 'Putih', 'Putih Hijau', 'Hijau'];

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    // Perubahan utama ada di method ini
    public function saveOrder(MidtransService $midtrans)
    {
        $validated = $this->validate([
            'fullName' => 'required|string|min:3',
            'phoneNumber' => 'required|string|min:10',
            'school' => 'required|string|min:3',
            'level' => 'required|string',
        ]);

        try {
            $order = DB::transaction(function () use ($midtrans, $validated) {

                $order = Order::create([
                    'event_id' => $this->event->id,
                    'user_id' => Auth::id(),
                    'order_code' => 'ORD-' . now()->timestamp . '-' . $this->event->id,
                    'quantity' => 1,
                    'total_price' => $this->event->ticket_price,
                    'status' => 'pending',
                    'customer_name' => $validated['fullName'],
                    'phone_number' => $validated['phoneNumber'],
                    'school' => $validated['school'],
                    'level' => $validated['level'],
                ]);

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_code,
                        'gross_amount' => $order->total_price,
                    ],
                    'item_details' => [
                        [
                            'id' => $this->event->id,
                            'price' => $this->event->ticket_price,
                            'quantity' => 1,
                            'name' => 'Tiket ' . $this->event->title,
                        ],
                    ],
                    'customer_details' => [
                        'first_name' => $order->customer_name,
                        'phone' => $order->phone_number,
                        'email' => Auth::user()->email,
                    ],
                    'enabled_payments' => [
                        'bca_va',
                        'echannel', // Ini untuk Mandiri Bill Payment
                        'gopay'
                    ]
                ];

                $snap = $midtrans->createTransaction($params);
                $order->update(['midtrans_token' => $snap->token]);

                return $order;
            });

            return $this->redirect(route('orders.payment', $order), navigate: true);
        } catch (Throwable $e) {
            // Jika ada error, kirimkan pesan ke pengguna
            session()->flash('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-order');
    }
}
