{{-- resources/views/avis/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laisser un avis — Commande #{{ $commande->id }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-6">
        {{-- Message/erreurs --}}
        @if (session('message'))
            <div class="mb-4 px-4 py-3 rounded bg-blue-50 text-blue-800">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded bg-red-50 text-red-700">
                @foreach ($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            {{-- Petit récap commande (optionnel) --}}
            <div class="border-b pb-4">
                <div class="text-sm text-gray-500">Commande #{{ $commande->id }}</div>
                <div class="text-sm text-gray-500">
                    Passée le {{ $commande->created_at?->format('d/m/Y H:i') }} —
                    Total : <span class="font-semibold">
                        {{ number_format($commande->montant_total, 2, ',', ' ') }} €
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('avis.store', $commande) }}" class="space-y-5">
                @csrf

                {{-- NOTE (1..5) --}}
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700">
                        Note
                    </label>
                    <select id="note" name="note" required
                            class="mt-1 block w-40 rounded border-gray-300">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('note') == $i)>
                                {{ $i }} / 5
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- COMMENTAIRE --}}
                <div>
                    <label for="commentaire" class="block text-sm font-medium text-gray-700">
                        Commentaire (optionnel)
                    </label>
                    <textarea id="commentaire" name="commentaire" rows="5"
                              class="mt-1 block w-full rounded border-gray-300"
                              placeholder="Racontez votre expérience...">{{ old('commentaire') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="px-6 py-2 rounded bg-green-700 text-white hover:bg-green-800">
                        Envoyer mon avis
                    </button>

                    <a href="{{ route('commandes.show', $commande) }}"
                       class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
