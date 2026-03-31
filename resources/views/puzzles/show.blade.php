<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ $puzzle->nom }}
        </h1>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row gap-8">

            {{-- Image --}}
            <div class="flex-1 flex items-center justify-center bg-gray-50 rounded-lg p-4">
                <img
                    src="{{ $puzzle->image_url ?? asset('img/puzzles/placeholder.png') }}"
                    alt="{{ $puzzle->nom }}"
                    class="max-h-[380px] w-auto object-contain"
                    loading="lazy"
                >
            </div>

            {{-- Infos produit --}}
            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <div class="text-sm text-gray-500 mb-3">
                        Catégorie :
                        @if($puzzle->categorie)
                            <a href="{{ route('categories.show', $puzzle->categorie->id) }}"
                               class="text-gray-800 font-medium hover:underline">
                                {{ $puzzle->categorie->nom }}
                            </a>
                        @else
                            —
                        @endif
                    </div>

                    <p class="text-gray-700 leading-relaxed">
                        {{ $puzzle->description ?: "Magnifique puzzle en bois." }}
                    </p>
                </div>

                <div class="mt-6">
                    <div class="text-3xl font-bold text-gray-900 mb-6">
                        {{ number_format($puzzle->prix, 2, ',', ' ') }} €
                    </div>

                    <form action="{{ route('panier.add') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="puzzle_id" value="{{ $puzzle->id }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Quantité
                            </label>
                            <input type="number" name="quantite" value="1" min="1"
                                   class="w-24 rounded border-gray-300" />
                        </div>
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center px-5 py-3 rounded-lg bg-black text-white font-semibold hover:bg-gray-800">
                            Ajouter au panier
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
