<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Catégories</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-6 grid sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->id) }}"
               class="bg-white rounded shadow p-4 hover:shadow-md transition">
                <div class="font-semibold text-green-900">{{ $cat->nom }}</div>
                <div class="text-sm text-green-800 mt-1">Voir les puzzles →</div>
            </a>
        @endforeach
    </div>
</x-app-layout>
