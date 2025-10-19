<div>
  <x-layout title="Tiket Saya">
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6">Riwayat Tiket Saya</h1>

      <div class="space-y-4">
        @forelse ($orders as $order)
          <div class="bg-white shadow-md rounded-lg p-4 flex justify-between items-center">
            <div>
              <h2 class="font-bold text-lg">{{ $order->event->title }}</h2>
              <p class="text-sm text-gray-600">Kode Order: {{ $order->order_code }}</p>
              <p class="text-sm text-gray-500">Tanggal: {{ $order->created_at->format('d M Y') }}</p>
            </div>
            <div>
              @if ($order->status === 'paid' && $order->hasMedia('etickets'))
                {{-- Tombol BARU untuk melihat tiket di browser --}}
                <a href="{{ route('tickets.show', $order) }}" target="_blank"
                  class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 mr-2">
                  Lihat E-Ticket
                </a>
                <button wire:click="downloadTicket({{ $order->id }})"
                  class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                  Download
                </button>
              @else
                {{-- Jika status adalah 'pending', tampilkan tombol aksi --}}
                @if ($order->status === 'pending')
                  <div class="flex items-center gap-2">
                    {{-- Tombol Batalkan --}}
                    <button wire:click="cancelOrder({{ $order->id }})"
                      wire:confirm="Anda yakin ingin membatalkan pesanan ini?"
                      class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600">
                      Batalkan
                    </button>

                    {{-- Tombol Bayar --}}
                    <a href="{{ route('orders.payment', $order) }}"
                      class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                      Bayar
                    </a>
                  </div>
                @else
                  {{-- Jika statusnya bukan 'paid' dan bukan 'pending' (misal: failed, expired), tampilkan badge --}}
                  <span
                    class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 text-sm font-semibold">{{ ucfirst($order->status) }}</span>
                @endif
              @endif
            </div>
          </div>
        @empty
          <p>Anda belum memiliki riwayat pembelian tiket.</p>
        @endforelse
      </div>
    </div>
  </x-layout>
</div>
