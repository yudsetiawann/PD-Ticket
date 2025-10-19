<x-filament-panels::page>
  <div class="space-y-6 max-w-md mx-auto">

    {{-- Area Scanner hanya muncul jika belum ada hasil scan --}}
    @if (!$scanResult)
      <div id="reader" class="w-full border-4 border-gray-300 rounded-lg overflow-hidden"></div>

      <div class="pt-4 border-t">
        <label for="manualCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Atau Masukkan Kode
          Manual:</label>
        <div class="flex items-center gap-2">
          <x-filament::input.wrapper>
            <x-filament::input type="text" id="manualCode" wire:model="manualCode" placeholder="TIX-XXXXXXXXXX" />
          </x-filament::input.wrapper>
          <x-filament::button wire:click="processManualCode" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="processManualCode">Submit</span>
            <span wire:loading wire:target="processManualCode">Memproses...</span>
          </x-filament::button>
        </div>
        @error('manualCode')
          <span class="text-sm text-danger-600 dark:text-danger-400 mt-1">{{ $message }}</span>
        @enderror
      </div>
    @endif

    {{-- Area untuk menampilkan hasil scan --}}
    @if ($scanResult)
      <x-filament::section>
        <x-slot name="heading">
          Hasil Scan
        </x-slot>

        <div @class([
            'p-4 rounded-lg', // Hapus text-center agar text-left di bawahnya bekerja
            'bg-success-100 dark:bg-success-500/10 text-success-800 dark:text-success-400' =>
                $resultColor === 'success',
            'bg-danger-100 dark:bg-danger-500/10 text-danger-800 dark:text-danger-400' =>
                $resultColor === 'danger',
            'bg-warning-100 dark:bg-warning-500/10 text-warning-800 dark:text-warning-400' =>
                $resultColor === 'warning',
        ])>
          <p class="font-bold text-lg text-center">{{ $scanResult }}</p> {{-- Pindahkan text-center ke sini --}}
          @if ($lastScannedOrder)
            <div class="text-sm mt-2 text-left space-y-1">
              {{-- Gunakan data relasi user yang sudah di-load --}}
              <p><strong>Nama:</strong> {{ $lastScannedOrder->user->name ?? $lastScannedOrder->customer_name }}</p>
              <p><strong>Event:</strong> {{ $lastScannedOrder->event->title }}</p>
              <p><strong>Kode Tiket:</strong> {{ $lastScannedOrder->ticket_code }}</p>
            </div>
          @endif
        </div>

        {{-- Tombol Scan Lagi --}}
        <div class="pt-4 text-center">
          <x-filament::button wire:click="resetScan" color="gray">
            Scan Tiket Lain
          </x-filament::button>
        </div>
      </x-filament::section>
    @endif
  </div>

  {{-- ====================================================================== --}}
  {{-- PINDAHKAN SCRIPT KE SINI (DI LUAR SEMUA KONDISIONAL @if) --}}
  {{-- ====================================================================== --}}
  @push('scripts')
  <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
  <script>
    let html5QrCode = null; // Definisikan di scope global
    const readerElementId = "reader";

    function startScanner() {
      const readerElement = document.getElementById(readerElementId);

      // Hanya mulai jika elemen #reader ada di DOM dan scanner belum berjalan
      if (readerElement && (!html5QrCode || !html5QrCode.isScanning)) {
        console.log('Memulai scanner...');
        html5QrCode = new Html5Qrcode(readerElementId);

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
          if (html5QrCode?.isScanning) {
            console.log('Scan berhasil, mengirim ke Livewire.');
            Livewire.dispatch('qr-code-scanned', { code: decodedText });
            stopScanner(); // Hentikan scanner setelah berhasil
          }
        };

        const config = {
          fps: 10,
          qrbox: { width: 250, height: 250 }
        };

        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
          .catch(err => {
            console.error(`Gagal memulai scanner: ${err}`);
            alert('Gagal memulai kamera. Pastikan izin sudah diberikan.');
          });
      }
    }

    function stopScanner() {
      if (html5QrCode && html5QrCode.isScanning) {
        console.log('Menghentikan scanner...');
        html5QrCode.stop()
          .then(ignore => {
            console.log('Scanner dihentikan.');
            html5QrCode = null; // Hapus instansi agar bisa dibuat ulang
          })
          .catch(err => {
            console.error('Gagal menghentikan scanner.', err);
          });
      }
    }

    // ======================================================
    // PERUBAHAN DI SINI
    // ======================================================

    document.addEventListener('livewire:initialized', () => {
      console.log('Livewire initialized. Mendaftarkan listeners.');

      // HANYA mendaftarkan listener di sini.
      // JANGAN panggil startScanner() di sini.

      @this.on('start-scanner', (event) => {
        console.log('Menerima event start-scanner');
        setTimeout(startScanner, 100);
      });

      @this.on('stop-scanner', (event) => {
        console.log('Menerima event stop-scanner');
        stopScanner();
      });
    });

    document.addEventListener('livewire:navigated', () => {
      console.log('Livewire navigated.');

      // Selalu hentikan scanner lama (jika ada sisa)
      stopScanner();

      // Dan mulai scanner baru.
      // Ini akan berjalan saat halaman dimuat pertama kali
      // dan setiap kali Anda navigasi ke halaman ini lagi (via SPA).
      startScanner();
    });

    // ======================================================
    // AKHIR DARI PERUBAHAN
    // ======================================================

    // Hentikan scanner jika pengguna meninggalkan halaman
    window.addEventListener('beforeunload', () => {
      stopScanner();
    });
  </script>
@endpush
</x-filament-panels::page>
