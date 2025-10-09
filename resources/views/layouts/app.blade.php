<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Ticketing' }}</title>

  {{-- Directive untuk CSS & JS jika menggunakan Vite --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Penting untuk styling Livewire --}}
  @livewireStyles
</head>

<body class="antialiased">

  {{-- Di sinilah konten komponen Livewire Anda akan disisipkan --}}
  {{ $slot }}

  {{-- Penting untuk fungsionalitas Livewire --}}
  @livewireScripts
</body>

</html>
