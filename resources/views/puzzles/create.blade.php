<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a puzzle') }}
        </h2>
    </x-slot>

    <x-puzzles-card>
        @if (session()->has('message'))
            <div class="mt-3 mb-4 list-disc list-inside text-sm text-gray-700">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('puzzles.store') }}" method="post">
            @csrf

            <!-- Nom -->
            <div>
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input id="nom" class="block mt-1 w-full"
                              type="text" name="nom" :value="old('nom')" required autofocus />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>

            <!-- Catégorie -->
            <div class="mt-4">
                <x-input-label for="categorie_id" :value="__('Catégorie')" />
                <select id="categorie_id" name="categorie_id" class="block mt-1 w-full" required>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->nom }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('categorie_id')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-textarea id="description" name="description" class="block mt-1 w-full">{{ old('description') }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Image (URL) -->
            <div class="mt-4">
                <x-input-label for="image" :value="__('Image')" />
                <x-text-input id="image" class="block mt-1 w-full"
                              type="text" name="image" :value="old('image')" />
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <!-- Prix -->
            <div class="mt-4">
                <x-input-label for="prix" :value="__('Prix')" />
                <x-text-input id="prix" class="block mt-1 w-full"
                              type="number" name="prix" :value="old('prix')" step="0.01" min="0" required />
                <x-input-error :messages="$errors->get('prix')" class="mt-2" />
            </div>

            <!-- Stock -->
            <div class="mt-4">
                <x-input-label for="stock" :value="__('Stock')" />
                <x-text-input id="stock" class="block mt-1 w-full"
                              type="number" name="stock" :value="old('stock')" min="0" required />
                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button class="ml-3">
                    {{ __('Send') }}
                </x-primary-button>
            </div>
        </form>
    </x-puzzles-card>
</x-app-layout>
