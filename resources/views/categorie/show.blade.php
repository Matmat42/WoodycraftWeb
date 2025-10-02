{{-- resources/views/puzzles/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ $puzzles->nom }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6">
        {{-- Image du puzzle --}}
        <img src="{{ asset('storage/' . $puzzle->image) }}" alt="{{ $puzzle->nom }}" class="w-full h-80 object-cover rounded mb-4">

        {{-- Description --}}
        <p class="text-gray-700 mb-4">{{ $puzzle->description }}</p>

        {{-- Prix --}}
        <p class="text-xl font-bold mb-6">€{{ number_format($puzzle->prix, 2) }}</p>

        {{-- Formulaire d'ajout au panier --}}
        <form method="POST" action="{{ route('cart.addPuzzle', $puzzle->id) }}">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                Ajouter au panier
            </button>
        </form>

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="mt-4 bg-green-100 text-green-800 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>
</x-app-layout>
