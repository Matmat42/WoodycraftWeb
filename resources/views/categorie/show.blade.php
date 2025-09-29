<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $categorie->nom }} - @lang('Produits')
        </h2>
    </x-slot>

    <div class="container py-6">
        <h3 class="text-2xl font-semibold">Produits de la catégorie : {{ $categorie->nom }}</h3>

        <!-- Affichage des puzzles -->
        <div class="grid grid-cols-3 gap-6 mt-4">
            @forelse ($categorie->puzzles as $puzzle)
                <div class="p-4 border rounded shadow">
                    <img src="{{ asset('storage/'.$puzzle->image) }}" alt="{{ $puzzle->nom }}" class="w-full h-40 object-cover rounded">
                    <h4 class="mt-2 text-lg font-semibold">{{ $puzzle->nom }}</h4>
                    <p class="text-gray-500">{{ $puzzle->description }}</p>
                    <div class="mt-2">
                        <span class="text-xl font-bold">{{ $puzzle->prix }} €</span>
                    </div>
                    <a href="{{ route('puzzles.show', $puzzle->id) }}" class="text-blue-500 hover:text-blue-700">Voir plus</a>
                </div>
            @empty
                <p class="text-gray-500">Aucun produit trouvé dans cette catégorie.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
