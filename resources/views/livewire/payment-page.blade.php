<div>

  <x-layout title="Pembayaran Tiket">

    <h1 class="text-2xl font-semibold mb-4">Pembayaran Tiket {{ $order->event->title }}</h1>
    {{-- Detail Pesanan --}}
    <div class="space-y-4">
      {{-- BARIS BARU: Menampilkan Nama Pembeli --}}
      <div class="flex justify-between items-center">
        <span class="text-gray-500">Nama Pembeli</span>
        <span class="font-semibold text-gray-800 text-right">{{ $order->customer_name }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-gray-500">Event</span>
        <span class="font-semibold text-gray-800 text-right">{{ $order->event->title }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-gray-500">Jumlah Tiket</span>
        <span class="font-semibold text-gray-800">{{ $order->quantity }} Tiket</span>
      </div>

      <hr class="border-gray-200 !my-6">

      <div class="flex justify-between items-center text-xl pb-3">
        <span class="font-medium text-gray-600">Total Pembayaran</span>
        <span class="font-bold text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
      </div>
    </div>


    <div class="flex justify-center">
      <button id="pay-button" class="bg-green-600 text-white px-4 py-2 rounded">
        Bayar Sekarang
      </button>
    </div>


    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
      document.getElementById('pay-button').addEventListener('click', function() {

        // Ganti '$order->snap_token' menjadi '$order->midtrans_token'

        window.snap.pay('{{ $order->midtrans_token }}', {

          onSuccess: function(result) {

            alert('Pembayaran sukses!');

            console.log(result);

            window.location.href = '/';

          },

          onPending: function(result) {

            alert('Menunggu pembayaran Anda...');

            console.log(result);

          },

          onError: function(result) {

            alert('Pembayaran gagal!');

            console.log(result);

          }

        });

      });
    </script>

  </x-layout>


</div>
