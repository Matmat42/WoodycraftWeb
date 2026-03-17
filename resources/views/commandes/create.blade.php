<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Valider ma commande</h2></x-slot>

    <div class="max-w-4xl mx-auto mt-6 grid gap-6 md:grid-cols-2">
        <div class="bg-white rounded shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Récapitulatif</h3>

            <ul class="space-y-3">
                @foreach($panier->lignes as $l)
                    <li class="flex justify-between">
                        <div>
                            <div class="font-medium">{{ $l->puzzle->nom }}</div>
                            <div class="text-sm text-gray-500">
                                Qté : {{ $l->quantite }} × {{ number_format($l->puzzle->prix, 2, ',', ' ') }} €
                            </div>
                        </div>
                        <div class="font-semibold">{{ number_format($l->total_ligne, 2, ',', ' ') }} €</div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4 pt-4 border-t flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>{{ number_format($panier->lignes->sum('total_ligne'), 2, ',', ' ') }} €</span>
            </div>

            <a href="{{ route('panier.index') }}" class="mt-4 inline-flex items-center text-sm text-gray-600 hover:text-gray-900 underline">
                ← Retour au panier
            </a>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Informations de commande</h3>

            @if ($errors->any())
                <div class="mb-4 text-red-700 bg-red-50 px-3 py-2 rounded">
                    @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
                </div>
            @endif

            <form id="orderForm" method="POST" action="{{ route('commandes.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="adresse_livraison_id" class="block text-sm font-medium text-gray-700">
                        Adresse de livraison
                    </label>
                    <select id="adresse_livraison_id" name="adresse_livraison_id" required class="mt-1 block w-full rounded border-gray-300">
                        @foreach($adresses as $a)
                            <option value="{{ $a->id }}" @selected(old('adresse_livraison_id', $defaultLivraisonId ?? null) == $a->id)>
                            {{ $a->numero }}, {{ $a->rue }}, {{ $a->code_postal }} {{ $a->ville }} ({{ $a->pays }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="adresse_facturation_id" class="block text-sm font-medium text-gray-700">
                        Adresse de facturation
                    </label>
                    <select id="adresse_facturation_id" name="adresse_facturation_id" required class="mt-1 block w-full rounded border-gray-300">
                        @foreach($adresses as $a)
                            <option value="{{ $a->id }}" @selected(old('adresse_facturation_id', $defaultFacturationId ?? null) == $a->id)>
                            {{ $a->numero }}, {{ $a->rue }}, {{ $a->code_postal }} {{ $a->ville }} ({{ $a->pays }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('adresses.create', ['next' => route('commandes.create')]) }}"
                   class="text-sm underline text-green-700">+ Ajouter une nouvelle adresse</a>

                <div>
                    <label for="mode_paiement" class="block text-sm font-medium text-gray-700">Moyen de paiement</label>
                    <select id="mode_paiement" name="mode_paiement" required class="mt-1 block w-full rounded border-gray-300">
                        <option value="paypal">PayPal</option>
                        <option value="cheque">Chèque</option>
                    </select>
                    <p id="paymentHelp" class="mt-2 text-sm text-gray-500">
                        Vous serez redirigé vers PayPal pour terminer le paiement.
                    </p>
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button id="submitOrder" type="submit"
                            class="inline-flex justify-center px-6 py-3 bg-green-600 text-white text-base font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none">
                        Payer avec PayPal ➜
                    </button>

                    <a href="{{ route('panier.index') }}" class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function () {
        const select = document.getElementById('mode_paiement');
        const btn    = document.getElementById('submitOrder');
        const form   = document.getElementById('orderForm');
        const help   = document.getElementById('paymentHelp');

        function refreshButton() {
            if (select.value === 'cheque') {
                btn.textContent = 'Générer la facture PDF 🧾';
                help.textContent = 'Une facture PDF sera générée avec les informations de la commande.';
            } else {
                btn.textContent = 'Payer avec PayPal ➜';
                help.textContent = 'Vous serez redirigé vers PayPal pour terminer le paiement.';
            }
        }
        select.addEventListener('change', refreshButton);
        refreshButton();

        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        });
    })();
    </script>
</x-app-layout>
