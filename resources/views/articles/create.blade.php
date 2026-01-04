<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un nouvel article') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- RETRAIT de 'overflow-hidden' ici pour permettre l'extension verticale --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('editor.articles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                        <select name="category_id" id="category_id" required
                                class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choisir --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Image de couverture</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    </div>

                    {{-- Tags : Structure flex-wrap robuste --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tags</label>
                        <div class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <label class="inline-flex items-center bg-white border border-gray-300 rounded px-3 py-2 cursor-pointer shadow-sm hover:bg-gray-100">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4"
                                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Contenu</label>
                        <textarea name="content" id="content" rows="10" required
                                  class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:ring-indigo-500 focus:border-indigo-500">{{ old('content') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('editor.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Annuler</a>
                        <x-primary-button>
                            {{ __('Publier l\'article') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
