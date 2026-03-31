{{-- resources/views/adresses/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter une adresse
        </h2>
    </x-slot>
    <div class="max-w-md mx-auto mt-8 bg-white rounded-lg shadow p-6">
        @if(session('success'))
            <div class="mb-4 text-gray-800 bg-gray-50 px-3 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('adresses.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="next" value="{{ request('next', route('commandes.create')) }}">
            <div>
                <label for="numero" class="block text-sm font-medium text-gray-700">Numéro *</label>
                <input type="text" name="numero" id="numero" value="{{ old('numero') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-600 focus:ring-gray-600">
                @error('numero') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="rue" class="block text-sm font-medium text-gray-700">Rue *</label>
                <input type="text" name="rue" id="rue" value="{{ old('rue') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-600 focus:ring-gray-600">
                @error('rue') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="ville" class="block text-sm font-medium text-gray-700">Ville *</label>
                <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-600 focus:ring-gray-600">
                @error('ville') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="code_postal" class="block text-sm font-medium text-gray-700">Code postal *</label>
                <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-600 focus:ring-gray-600">
                @error('code_postal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="pays" class="block text-sm font-medium text-gray-700">Pays *</label>
                <input type="text" name="pays" id="pays" value="{{ old('pays', 'France') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-600 focus:ring-gray-600">
                @error('pays') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="pt-2 flex items-center gap-3">
                <button type="submit"
                        style="background-color:#000; color:#fff; padding:0.6rem 1.25rem; border-radius:0.375rem; font-weight:600; border:none; cursor:pointer;"
                        onmouseover="this.style.backgroundColor='#374151'"
                        onmouseout="this.style.backgroundColor='#000'">
                    Enregistrer et continuer
                </button>
                <a href="{{ request('next', route('commandes.create')) }}"
                   style="background-color:#000; color:#fff; padding:0.5rem 1rem; border-radius:0.375rem; border:1px solid #1f2937; text-decoration:none; display:inline-block;"
                   onmouseover="this.style.backgroundColor='#374151'"
                   onmouseout="this.style.backgroundColor='#000'">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
