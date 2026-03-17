<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ $puzzle->nom }}
        </h1>
    </x-slot>

    <div class="bg-white rounded shadow p-4 flex items-center justify-center">
    <img
        src="{{ $puzzle->image_url ?? asset('img/puzzles/placeholder.png') }}"
        alt="{{ $puzzle->nom }}"
        class="max-h-[420px] w-auto object-contain"
        loading="lazy"
    >
</div>


        {{-- Infos produit --}}
        <div class="bg-white rounded shadow p-6">
            <div class="text-sm text-gray-600 mb-2">
                Catégorie :
                @if($puzzle->categorie)
                    <a href="{{ route('categories.show', $puzzle->categorie->id) }}"
                       class="text-blue-700 hover:underline">
                        {{ $puzzle->categorie->nom }}
                    </a>
                @else
                    —
                @endif
            </div>

            <p class="text-gray-700 leading-relaxed">
                {{ $puzzle->description ?: "Magnifique puzzle en bois." }}
            </p>

            <div class="mt-6 text-3xl font-bold">
                {{ number_format($puzzle->prix, 2, ',', ' ') }} €
            </div>

            {{-- Ajouter au panier --}}
            <form action="{{ route('panier.add') }}" method="POST" class="mt-6 space-y-3">
                @csrf
                <input type="hidden" name="puzzle_id" value="{{ $puzzle->id }}">

                <label class="block text-sm font-medium text-gray-700">
                    Quantité
                </label>
                <input type="number" name="quantite" value="1" min="1"
                       class="w-24 rounded border-gray-300" />

                <div class="pt-2">
                    <button type="submit"
                            class="inline-flex items-center px-5 py-3 rounded-lg bg-blue-700 text-white font-semibold hover:bg-blue-800">
                        Ajouter au panier
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
