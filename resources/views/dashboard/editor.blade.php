<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Dashboard Éditeur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages de succès --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Statistiques --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Articles</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalArticles }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Commentaires</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $totalComments }}</p>
                    </div>
                </div>
            </div>

            {{-- Bouton créer un article --}}
            <div class="mb-6">
                <a href="{{ route('editor.articles.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Créer un nouvel article
                </a>
            </div>

            {{-- Liste des articles --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">Mes Articles</h3>

                    @forelse($articles as $article)
                        <div class="border-b border-gray-200 py-4 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800">
                                        <a href="{{ route('articles.show', $article) }}" class="hover:text-blue-600">
                                            {{ $article->title }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Catégorie: <span class="font-medium">{{ $article->category->name }}</span> |
                                        Commentaires: <span class="font-medium">{{ $article->comments_count }}</span> |
                                        Publié le {{ $article->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    {{-- Bouton modifier --}}
                                    <a href="{{ route('editor.articles.edit', $article) }}"
                                       class="px-3 py-1 bg-yellow-500 text-yellow text-sm rounded hover:bg-yellow-600">
                                        Modifier
                                    </a>
                                    {{-- Bouton supprimer --}}
                                    <form action="{{ route('editor.articles.destroy', $article) }}"
                                          method="POST"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-red text-sm rounded hover:bg-red-600">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">Vous n'avez pas encore créé d'articles.</p>
                    @endforelse

                    {{-- Pagination --}}
                    @if($articles->hasPages())
                        <div class="mt-6">
                            {{ $articles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
