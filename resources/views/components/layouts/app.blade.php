<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-g">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Page Title' }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles
</head>

<body class="antialiased">

  {{-- Di sinilah konten komponen Livewire Anda akan ditampilkan --}}
  {{ $slot }}

  @livewireScripts
</body>

</html>
