<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Merci pour votre commande</h2></x-slot>
    <div class="max-w-3xl mx-auto mt-8 bg-white rounded shadow p-8 text-center">
        <h3 class="text-xl font-semibold mb-2">Votre commande #{{ $commande->id }} a bien été enregistrée.</h3>
        <p class="text-gray-600">Un récapitulatif est disponible dans "Mes commandes".</p>
        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('commandes.show', $commande) }}"
               style="background-color:#000; color:#fff; padding:0.5rem 1.25rem; border-radius:0.375rem; text-decoration:none;"
               onmouseover="this.style.backgroundColor='#374151'"
               onmouseout="this.style.backgroundColor='#000'">
                Voir les détails
            </a>
            <a href="{{ route('accueil') }}"
               style="background-color:#fff; color:#000; padding:0.5rem 1.25rem; border-radius:0.375rem; border:1px solid #000; text-decoration:none;"
               onmouseover="this.style.backgroundColor='#f3f4f6'"
               onmouseout="this.style.backgroundColor='#fff'">
                Retour à l'accueil
            </a>
        </div>
    </div>
</x-app-layout>
