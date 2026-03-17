{{-- resources/views/commandes/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Commande #{{ $commande->id }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto p-6 space-y-6">

        {{-- Feedback --}}
        @if (session('success'))
            <div class="bg-green-50 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if (session('message'))
            <div class="bg-blue-50 text-blue-800 px-4 py-3 rounded">{{ session('message') }}</div>
        @endif

        {{-- Entête commande --}}
        <div class="bg-white rounded-lg shadow p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                <div>
                    <div class="text-sm text-gray-500">Passée le</div>
                    <div class="font-semibold">
                        {{ optional($commande->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Montant total</div>
                    @php
                        // fallback si jamais le champ n'était pas rempli
                        $totalAffiche = $commande->montant_total ?? $commande->lignes->sum('total_ligne');
                    @endphp
                    <div class="text-lg font-bold">
                        {{ number_format($totalAffiche, 2, ',', ' ') }} €
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Paiement</div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-medium">
                        {{ ucfirst($commande->mode_paiement) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('commandes.facture', $commande) }}"
                   class="px-4 py-2 rounded bg-green-700 text-white hover:bg-green-800">
                    Facture PDF
                </a>
                <a href="{{ route('accueil') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Continuer mes achats
                </a>
            </div>
        </div>

        {{-- Adresses --}}
        <div class="grid gap-6 md:grid-cols-2">
            <div class="bg-white rounded-lg shadow p-5">
                <h3 class="font-semibold mb-2">Adresse de livraison</h3>
                @if($commande->adresseLivraison)
                    <div>{{ $commande->adresseLivraison->rue }}</div>
                    <div>{{ $commande->adresseLivraison->code_postal }} {{ $commande->adresseLivraison->ville }}</div>
                    <div>{{ $commande->adresseLivraison->pays }}</div>
                @else
                    <div class="text-gray-500">—</div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-5">
                <h3 class="font-semibold mb-2">Adresse de facturation</h3>
                @if($commande->adresseFacturation)
                    <div>{{ $commande->adresseFacturation->rue }}</div>
                    <div>{{ $commande->adresseFacturation->code_postal }} {{ $commande->adresseFacturation->ville }}</div>
                    <div>{{ $commande->adresseFacturation->pays }}</div>
                @else
                    <div class="text-gray-500">—</div>
                @endif
            </div>
        </div>

        {{-- Lignes --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-5 border-b">
                <h3 class="font-semibold">Articles commandés</h3>
            </div>

            <div class="divide-y">
                @forelse($commande->lignes as $l)
                    <div class="p-5 grid gap-2 md:grid-cols-4 md:items-center">
                        <div class="md:col-span-2">
                            <div class="font-medium">{{ $l->puzzle?->nom }}</div>
                            <div class="text-sm text-gray-500">{{ $l->puzzle?->categorie?->nom }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Qté : {{ $l->quantite }}</div>
                        <div class="text-right font-semibold">
                            {{ number_format($l->total_ligne, 2, ',', ' ') }} €
                            <div class="text-xs text-gray-500">
                                PU : {{ number_format($l->puzzle?->prix ?? 0, 2, ',', ' ') }} €
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-gray-500">Aucun article.</div>
                @endforelse
            </div>

            <div class="p-5 border-t">
                <div class="flex justify-end">
                    <div class="w-full md:w-64 space-y-1">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Sous-total</span>
                            <span>{{ number_format($commande->lignes->sum('total_ligne'), 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span>{{ number_format($totalAffiche, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bloc avis --}}
            @php
                // Récupère l'avis de l'utilisateur connecté (la relation avis est hasMany)
                $avis = $commande->relationLoaded('avis')
                    ? $commande->avis->firstWhere('user_id', auth()->id())
                    : $commande->avis()->where('user_id', auth()->id())->first();
            @endphp

            <div class="p-5 border-t">
                <h3 class="font-semibold mb-3">Laisser un avis</h3>

                @if(!$avis)
                    <a href="{{ route('avis.create', $commande) }}"
                       class="inline-flex items-center px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-800">
                        Écrire un avis
                    </a>
                @else
                    <div class="rounded border bg-amber-50 text-amber-900 p-4">
                        <div class="font-semibold mb-1">Votre avis</div>
                        <div class="text-sm mb-1">Note : <strong>{{ $avis->note }}</strong>/5</div>
                        @if(filled($avis->commentaire))
                            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $avis->commentaire }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
