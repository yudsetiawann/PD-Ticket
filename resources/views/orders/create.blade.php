<x-layout title="Beli Tiket">
  <h1 class="text-2xl font-semibold mb-4">Beli Tiket: {{ $event->title }}</h1>

  <form method="POST" action="{{ route('orders.store', $event) }}" class="space-y-4 max-w-md">
    @csrf
    <div>
      <label class="block font-medium">Jumlah Tiket</label>
      <input type="number" name="quantity" class="border rounded w-full px-3 py-2" min="1"
        max="{{ $event->ticket_quota - $event->ticket_sold }}" required>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lanjut Bayar</button>
  </form>
</x-layout>
