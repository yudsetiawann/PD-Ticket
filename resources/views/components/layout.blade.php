<div>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>{{ $title ?? 'Ticketing App' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
  </head>

  <body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Navbar sederhana --}}
    <nav class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
      <a href="/" class="font-bold text-lg text-blue-600">E-Tick PD</a>

      <div class="flex items-center gap-4">
        @auth
          <div x-data="{ open: false }" class="relative inline-block text-left">
            <!-- Tombol dropdown -->
            <button @click="open = !open"
              class="flex items-center gap-2 text-gray-700 font-medium hover:text-blue-600 focus:outline-none transition">
              <span>{{ auth()->user()->name }}</span>
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>
            </button>

            <!-- Dropdown menu -->
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-150"
              x-transition:enter-start="opacity-0 transform scale-95"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-100"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-95"
              class="absolute left-[-10px] mt-2 w-44 bg-white border border-gray-100 rounded-lg shadow-lg py-1 z-50">

              <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                My Profile
              </a>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                  Logout
                </button>
              </form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a>
          <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Daftar</a>
        @endauth
      </div>
    </nav>

    {{-- Konten utama --}}
    <main class="flex-1 container mx-auto px-6 py-8">
      {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 text-center py-4 text-sm text-gray-600">
      &copy; {{ date('Y') }} Ticketing App. All rights reserved.
    </footer>
    @livewireScripts
  </body>

  </html>
</div>
