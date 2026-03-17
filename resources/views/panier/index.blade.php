<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mon panier</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 p-6 bg-white rounded shadow">
        @if (session('success'))
            <div class="mb-3 text-green-600">{{ session('success') }}</div>
        @endif
        @if (session('message'))
            <div class="mb-3 text-green-700">{{ session('message') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-3 text-red-600">
                @foreach ($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        @if(!$panier || $panier->lignes->isEmpty())
            <div class="rounded border bg-amber-50 text-amber-900 p-4">Aucun article.</div>
        @else
            <ul class="space-y-3">
                @foreach($panier->lignes as $l)
                    <li class="flex items-center gap-3">
                        <span class="w-64">
                            {{ $l->puzzle->nom }}
                            <span class="block text-xs text-gray-500">
                                @if(isset($l->puzzle->stock))
                                    Stock dispo : {{ $l->puzzle->stock }}
                                @endif
                            </span>
                        </span>

                        <form method="POST" action="{{ route('panier.update', $l) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="quantite" min="1"
                                   max="{{ $l->puzzle->stock ?? 999999 }}"
                                   value="{{ $l->quantite }}" class="w-24 border rounded">
                            <button class="px-2 py-1 bg-blue-600 text-white rounded">Maj</button>
                        </form>

                        <span class="ml-auto">{{ number_format($l->total_ligne, 2, ',', ' ') }} €</span>

                        <form method="POST" action="{{ route('panier.remove', $l) }}">
                            @csrf @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded">X</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            <p class="mt-4 font-semibold">
                Total : {{ number_format($panier->total, 2, ',', ' ') }} €
            </p>

            <a href="{{ route('commandes.create') }}"
               class="inline-block mt-4 px-5 py-2 bg-[var(--wc-primary)] text-white rounded hover:bg-[var(--wc-primary-dark)]">
                Passer commande
            </a>
        @endif
    </div>
</x-app-layout>
