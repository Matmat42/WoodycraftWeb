<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tableau de bord</h2>
    </x-slot>

    {{-- HÉRO bannière --}}
    <div class="max-w-6xl mx-auto mt-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-black">
                Découvrez notre gamme de puzzle 3D
            </h3>
            <p class="mt-1 text-gray-700">
                
            </p>

            <a href="{{ route('panier.index') }}"
               class="inline-flex mt-4 px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                Voir mon panier
            </a>
        </div>
    </div>

    {{-- Catégories mises en avant --}}
    <div class="max-w-6xl mx-auto mt-8">
        <h3 class="text-lg font-semibold mb-3 text-black">Catégories</h3>

        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($categories as $cat)
                <a href="{{ route('categories.show', $cat->id) }}"
                   class="bg-white rounded shadow p-4 hover:shadow-md transition">
                    <div class="font-semibold text-black">{{ $cat->nom }}</div>
                    <div class="text-sm text-gray-700 mt-1">Voir la catégorie →</div>
                </a>
            @empty
                <div class="text-sm text-gray-700 col-span-full">Aucune catégorie.</div>
            @endforelse
        </div>
    </div>

    {{-- Produits récents --}}
    <div class="max-w-6xl mx-auto mt-10 mb-10">
        <h3 class="text-lg font-semibold mb-3 text-black">Produits</h3>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($produits as $p)
                <div class="bg-white rounded shadow p-4">
                    <div class="font-semibold text-black">{{ $p->nom }}</div>
                    <div class="text-gray-700 mt-1">
                        {{ number_format($p->prix, 2, ',', ' ') }} €
                    </div>
                    <a href="{{ route('categories.show', $p->categorie_id) }}"
                       class="inline-block mt-3 text-sm text-black underline">
                        Voir le puzzle
                    </a>
                </div>
            @empty
                <div class="text-sm text-gray-700 col-span-full">Aucun produit pour le moment.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
