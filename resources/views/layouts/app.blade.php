<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WoodyCraft') }}</title>

    {{-- Fonts (Poppins) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- App CSS/JS (Breeze) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Palette + font --}}
    <style>
      :root{
        --wc-cream:#FBF7E9; --wc-accent:#E8DFB5; --wc-primary:#1E6E3E;
        --wc-primary-dark:#124D2A; --wc-danger:#D94141; --wc-text:#1B1B1B;
      }
      html,body{font-family:"Poppins",ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";}
    </style>
</head>
<body class="min-h-screen bg-[var(--wc-cream)] text-[var(--wc-text)] antialiased">

    {{-- Navigation --}}
    @include('layouts.navigation')

    {{-- Header slot --}}
    @isset($header)
        <header class="bg-[var(--wc-cream)]">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Main --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Flash messages (succès / erreur) optionnels --}}
    @if(session('message'))
        <div class="fixed bottom-6 right-6 bg-green-700 text-white px-4 py-2 rounded shadow">
            {{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fixed bottom-6 right-6 bg-red-600 text-white px-4 py-2 rounded shadow">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>
