<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tableau de bord</h2>
    </x-slot>

    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Catégories</h3>

        @isset($categories)
            @if($categories->count())
                <ul class="list-disc pl-6 space-y-1">
                    @foreach($categories as $categorie)
                        <li>
                            <a class="text-indigo-600 underline"
                               href="{{ route('categories.show', $categorie->id) }}">
                                {{ $categorie->nom }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Aucune catégorie trouvée.</p>
            @endif
        @else
            <p>Pas de catégories chargées (vérifie la route /dashboard).</p>
        @endisset
    </div>
</x-app-layout>
