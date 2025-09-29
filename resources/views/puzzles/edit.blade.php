<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editer un puzzle') }}
        </h2>
    </x-slot>

    <x-puzzles-card>
        {{-- Message de réussite --}}
        @if (session()->has('message'))
            <div class="mt-3 mb-4 list-disc list-inside text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('puzzles.update', $puzzle->id) }}" method="post">
            @csrf
            @method('put')

            {{-- Titre --}}
            <div>
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input 
                    id="nom" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="nom" 
                    :value="old('nom', $puzzle->nom)" 
                    required 
                    autofocus 
                />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>

            {{-- Categorie --}}
            <div class="mt-4">
                <x-input-label for="categorie" :value="__('Categorie')" />
                <x-textarea 
                    class="block mt-1 w-full" 
                    id="categorie" 
                    name="categorie"
                >{{ old('categorie', $puzzle->categorie) }}</x-textarea>
                <x-input-error :messages="$errors->get('categorie')" class="mt-3" />
            </div>

            {{-- Description --}}
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-textarea 
                    class="block mt-1 w-full" 
                    id="description" 
                    name="description"
                >{{ old('description', $puzzle->description) }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-4" />
            </div>

            {{-- Image --}}
            <div class="mt-4">
                <x-input-label for="image" :value="__('Image')" />
                <x-textarea 
                    class="block mt-1 w-full" 
                    id="image" 
                    name="image"
                >{{ old('image', $puzzle->image) }}</x-textarea>
                <x-input-error :messages="$errors->get('image')" class="mt-5" />
            </div>

            {{-- Prix --}}
            <div class="mt-4">
                <x-input-label for="prix" :value="__('Prix')" />
                <x-textarea 
                    class="block mt-1 w-full" 
                    id="prix" 
                    name="prix"
                >{{ old('prix', $puzzle->prix) }}</x-textarea>
                <x-input-error :messages="$errors->get('prix')" class="mt-6" />
            </div>

            <button type="submit" style="padding: 10px 20px; background-color: blue; color: white;">
            Mettre à jour le puzzle
            </button>
        </form>
    </x-puzzles-card>
</x-app-layout>
