<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catégorie : {{ $categorie->nom }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto p-6">
        @if($puzzles->isEmpty())
            <div class="rounded border bg-amber-50 text-amber-900 p-4">
                Aucun puzzle dans cette catégorie.
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($puzzles as $p)
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col">
                        <div class="aspect-[4/3] bg-gray-50 rounded mb-3 overflow-hidden flex items-center justify-center">
                            @php
                                // normalise le chemin tel qu'enregistré en base (img/puzzles/xxx.png, etc.)
                                $imgPath = $p->image ? ltrim(str_replace('\\','/',$p->image), '/') : null;
                            @endphp

                            @if($imgPath)
                                <img
                                    src="{{ asset($imgPath) }}"
                                    alt="{{ $p->nom }}"
                                    class="w-full h-full object-contain"
                                    loading="lazy"
                                >
                            @else
                                <span class="text-sm text-gray-400">Aucune image</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold mb-1">{{ $p->nom }}</h3>
                        <div class="text-sm text-gray-500 mb-2">
                            {{ $p->categorie->nom ?? '' }}
                        </div>

                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-base font-bold">
                                {{ number_format($p->prix, 2, ',', ' ') }} €
                            </span>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('puzzles.show', $p) }}"
                                   class="px-3 py-2 rounded border border-green-700 text-green-700 hover:bg-green-50">
                                    Voir
                                </a>

                                @auth
                                    <form method="POST" action="{{ route('panier.add') }}">
                                        @csrf
                                        <input type="hidden" name="puzzle_id" value="{{ $p->id }}">
                                        <input type="hidden" name="quantite" value="1">
                                        <button class="px-3 py-2 rounded bg-green-700 text-white hover:bg-green-800">
                                            Ajouter
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
