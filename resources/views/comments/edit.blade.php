<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier mon commentaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">Article : {{ $comment->article->title }}</h3>

                <form action="{{ route('comments.update', $comment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Votre commentaire</label>
                        <textarea name="content" id="content" rows="5" required
                                  class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:ring-indigo-500 focus:border-indigo-500">{{ old('content', $comment->content) }}</textarea>
                        @error('content')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('articles.show', $comment->article_id) }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Annuler</a>
                        <x-primary-button>
                            {{ __('Mettre à jour') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
