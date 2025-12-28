<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $article->title }}
            </h2>
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Retour aux articles
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages de succès --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Article --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                {{-- Image --}}
                @if($article->image_path)
                    <img src="{{ asset('storage/' . $article->image_path) }}"
                         alt="{{ $article->title }}"
                         class="w-full h-96 object-cover">
                @endif

                <div class="p-6">
                    {{-- Titre --}}
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

                    {{-- Métadonnées --}}
                    <div class="flex items-center text-sm text-gray-600 mb-6 space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Par <span class="font-medium ml-1">{{ $article->user->name }}</span>
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            {{ $article->created_at->format('d/m/Y') }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                            </svg>
                            {{ $article->comments->count() }} commentaire(s)
                        </span>
                    </div>

                    {{-- Catégorie et Tags --}}
                    <div class="mb-6">
                        <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mr-2">
                            {{ $article->category->name }}
                        </span>
                        @foreach($article->tags as $tag)
                            <a href="{{ route('articles.by-tag', $tag->slug) }}"
                               class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full hover:bg-gray-200">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Contenu --}}
                    <div class="prose max-w-none text-gray-800 leading-relaxed">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    {{-- Boutons d'action (si c'est l'auteur) --}}
                    @can('update-article', $article)
                        <div class="mt-6 pt-6 border-t border-gray-200 flex space-x-3">
                            <a href="{{ route('editor.articles.edit', $article) }}"
                               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Modifier cet article
                            </a>
                            <form action="{{ route('editor.articles.destroy', $article) }}"
                                  method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                    Supprimer cet article
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>

            {{-- Section Commentaires --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Commentaires ({{ $article->comments->count() }})
                    </h2>

                    {{-- Formulaire d'ajout de commentaire --}}
                    @auth
                        <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                            <form action="{{ route('comments.store', $article) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ajouter un commentaire
                                    </label>
                                    <textarea
                                        name="content"
                                        id="content"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                                        placeholder="Écrivez votre commentaire ici..."
                                        required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Publier le commentaire
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-8 bg-blue-50 border border-blue-200 p-4 rounded-lg text-center">
                            <p class="text-gray-700">
                                Vous devez être
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">connecté</a>
                                pour commenter.
                            </p>
                        </div>
                    @endauth

                    {{-- Liste des commentaires --}}
                    <div class="space-y-4">
                        @forelse($article->comments as $comment)
                            <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                            <span class="mx-2 text-gray-400">•</span>
                                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                    </div>

                                    {{-- Boutons d'action (si c'est l'auteur du commentaire) --}}
                                    @can('delete-comment', $comment)
                                        <div class="ml-4">
                                            <form action="{{ route('comments.destroy', $comment) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">
                                Aucun commentaire pour le moment. Soyez le premier à commenter !
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
