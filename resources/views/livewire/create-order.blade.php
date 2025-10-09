<div>
  <div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-2">Pesan Tiket untuk: {{ $event->title }}</h1>
    <p class="text-gray-600 mb-6">Silakan isi data diri Anda untuk melanjutkan pemesanan.</p>

    <div class="bg-white p-6 rounded-lg shadow-md">
      <form wire:submit="saveOrder">
        {{-- Nama Lengkap --}}
        <div class="mb-4">
          <label for="fullName" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
          <input type="text" id="fullName" wire:model="fullName"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          @error('fullName')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        {{-- Nomor Telepon --}}
        <div class="mb-4">
          <label for="phoneNumber" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
          <input type="tel" id="phoneNumber" wire:model="phoneNumber"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          @error('phoneNumber')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        {{-- Ranting/Sekolah --}}
        <div class="mb-4">
          <label for="school" class="block text-sm font-medium text-gray-700">Ranting / Sekolah</label>
          <input type="text" id="school" wire:model="school"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          @error('school')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        {{-- Tingkatan --}}
        <div class="mb-4">
          <label for="level" class="block text-sm font-medium text-gray-700">Tingkatan</label>
          <select id="level" wire:model="level"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">-- Pilih Tingkatan --</option>
            @foreach ($levels as $levelOption)
              <option value="{{ $levelOption }}">{{ $levelOption }}</option>
            @endforeach
          </select>
          @error('level')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        {{-- Tombol Submit --}}
        <div class="mt-6">
          <button type="submit"
            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <span wire:loading.remove>Lanjutkan Pemesanan</span>
            <span wire:loading>Memproses...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
