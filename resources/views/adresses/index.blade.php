<x-guest-layout>
    <h1 class="text-2xl font-bold mb-4">Liste des adresses</h1>

    <a href="{{ route('adresses.create', ['next' => route('commandes.create')]) }}"
   class="btn">Ajouter une adresse</a>
   
    <ul class="mt-4 space-y-2">
        @forelse($adresses as $adresse)
            <li>
                {{ $adresse->numero }} {{ $adresse->rue }}, {{ $adresse->ville }}
                ({{ $adresse->code_postal }} - {{ $adresse->pays }})
            </li>
        @empty
            <li>Aucune adresse.</li>
        @endforelse
    </ul>
</x-guest-layout>
