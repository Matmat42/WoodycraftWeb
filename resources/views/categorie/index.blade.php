<!-- resources/views/dashboard.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('Liste des catégories')
        </h2>
    </x-slot>

    <div class="container flex justify-center mx-auto">
        <div class="flex flex-col">
            <div class="w-full">
                <div class="border-b border-gray-200 shadow pt-6">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-2 text-xs text-gray-500">#</th>
                                <th class="px-2 py-2 text-xs text-gray-500">Nom</th>
                                <th class="px-2 py-2 text-xs text-gray-500">Description</th>
                                <th class="px-2 py-2 text-xs text-gray-500"></th>
                                <th class="px-2 py-2 text-xs text-gray-500"></th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @if($categories && $categories->count() > 0)
                                @foreach ($categories as $category)
                            <tr class="whitespace-nowrap">
                                <td class="px-4 py-4 text-sm text-gray-500">{{ $category->id }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500">{{ $category->nom }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500">{{ $category->description }}</td>
                            </tr>
                                @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center text-sm text-gray-500">Aucune catégorie trouvée.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
