<div>
  <x-layout title="Daftar Event">
    <h1 class="text-2xl font-semibold mb-6">Daftar Event</h1>

    <div class="grid md:grid-cols-3 gap-6">
      @forelse($events as $event)
        <div class="bg-white shadow rounded-lg p-4">
          @if ($event->getFirstMediaUrl('thumbnails'))
            <img src="{{ $event->getFirstMediaUrl('thumbnails') }}" alt="{{ $event->title }}"
              class="rounded mb-3 w-full h-48 object-cover">
          @endif
          <h2 class="font-bold text-lg">{{ $event->title }}</h2>
          <p class="text-sm text-gray-600">{{ $event->location }}</p>
          <p class="text-gray-500 text-sm">{{ $event->starts_at->format('d M Y') }}</p>
          <a href="{{ route('orders.create', $event) }}"
            class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded">Beli Tiket</a>
        </div>
      @empty
        <p>Tidak ada event tersedia.</p>
      @endforelse
    </div>
  </x-layout>
</div>
