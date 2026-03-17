<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Merci pour votre commande</h2></x-slot>

    <div class="max-w-3xl mx-auto mt-8 bg-white rounded shadow p-8 text-center">
        <div class="text-3xl mb-2">🎉</div>
        <h3 class="text-xl font-semibold mb-2">Votre commande #{{ $commande->id }} a bien été enregistrée.</h3>
        <p class="text-gray-600">Un récapitulatif est disponible dans “Mes commandes”.</p>

        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('commandes.show', $commande) }}"
               class="px-5 py-2 rounded bg-[var(--wc-primary)] text-white hover:bg-[var(--wc-primary-dark)]">
                Voir les détails
            </a>
            <a href="{{ route('accueil') }}"
               class="px-5 py-2 rounded border border-amber-300 text-[var(--wc-primary-dark)] hover:bg-amber-100">
                Retour à l’accueil
            </a>
        </div>
    </div>
</x-app-layout>
