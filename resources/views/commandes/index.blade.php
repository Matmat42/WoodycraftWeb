<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes commandes</h2></x-slot>

    <div class="max-w-6xl mx-auto p-6">
        @if($commandes->isEmpty())
            <div class="bg-amber-50 text-amber-900 border border-amber-200 rounded p-5">
                Vous n’avez pas encore passé de commande.
                <a href="{{ route('categories.index') }}" class="underline font-semibold text-green-700">
                    Parcourir les catégories
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passée le</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($commandes as $c)
                        <tr>
                            <td class="px-4 py-3 font-semibold">#{{ $c->id }}</td>
                            <td class="px-4 py-3">{{ $c->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 font-bold">{{ number_format($c->montant_total, 2, ',', ' ') }} €</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 rounded-full text-sm bg-amber-100 text-amber-800">
                                    {{ ucfirst($c->mode_paiement) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('commandes.show', $c) }}" class="inline-flex items-center px-3 py-2 rounded border border-green-700 text-green-700 hover:bg-green-50">
                                    Détail
                                </a>
                                <a href="{{ route('commandes.facture', $c) }}" class="ml-2 inline-flex items-center px-3 py-2 rounded bg-green-700 text-white hover:bg-green-800">
                                    Facture PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
