<div>
  <x-layout :title="$event->title">
    <div class="container mx-auto px-4 py-8">
      <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <img src="{{ $event->getFirstMediaUrl('thumbnails') }}" alt="{{ $event->title }}"
          class="rounded-lg w-full h-72 object-cover mb-4">
        <h1 class="text-4xl font-bold text-gray-800">{{ $event->title }}</h1>
        <div class="flex items-center text-gray-500 mt-2">
          <span>{{ $event->starts_at->format('d M Y') }}</span>
          <span class="mx-2">|</span>
          <span>{{ $event->location }}</span>
        </div>
        <div class="mt-6">
          <a href="{{ route('orders.create', $event) }}"
            class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-green-700">
            Beli Tiket (Rp {{ number_format($event->ticket_price, 0, ',', '.') }})
          </a>
        </div>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-8">
          <div>
            <h2 class="text-2xl font-semibold border-b pb-2 mb-4">Deskripsi Event</h2>
            <div class="prose max-w-none text-gray-700">
              {!! nl2br(e($event->description)) !!}
              {{-- {{ $event->description }} --}}
            </div>
          </div>

          @if ($event->hasMedia('gallery'))
            <div>
              <h2 class="text-2xl font-semibold border-b pb-2 mb-4">📸 Galeri Foto</h2>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($event->getMedia('gallery') as $media)
                  <div class="overflow-hidden rounded-lg shadow-md">
                    <img src="{{ $media->getUrl() }}" alt="Galeri {{ $event->title }}"
                      class="w-full h-40 object-cover hover:scale-110 transition-transform duration-300">
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <div>
            <h2 class="text-2xl font-semibold border-b pb-2 mb-4">Lokasi Acara</h2>
            @if ($event->location_map_link)
              <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                <iframe src="{{ $event->location_map_link }}" width="100%" height="450" style="border:0;"
                  allowfullscreen="" loading="lazy"></iframe>
              </div>
            @else
              <p>{{ $event->location }}</p>
            @endif
          </div>
        </div>

        <div class="md:col-span-1">
          <div class="bg-white shadow-lg rounded-lg p-6 space-y-4">
            <h3 class="text-xl font-semibold">Informasi</h3>
            <div class="border-t"></div>
            <div>
              <h4 class="font-semibold text-gray-800">Narahubung</h4>
              <p class="text-gray-600">{{ $event->contact_person_name }}</p>
              <p class="text-blue-600">{{ $event->contact_person_phone }}</p>
            </div>
            <div>
              <h4 class="font-semibold text-gray-800">Sisa Tiket</h4>
              <p class="text-gray-600">{{ $event->ticket_quota }} tiket</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </x-layout>
</div>
