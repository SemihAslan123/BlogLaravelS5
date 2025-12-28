<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(isset($category))
                Catégorie : {{ $category->name }}
            @elseif(isset($tag))
                Tag : {{ $tag->name }}
            @else
                {{ __('Tous les articles') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Sidebar catégories et tags --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                {{-- Catégories --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <h3 class="font-semibold text-lg mb-3">Catégories</h3>
                    <ul class="space-y-2">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('articles.by-category', $cat->slug) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Liste des articles --}}
                <div class="md:col-span-3">
                    @if($articles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($articles as $article)
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    {{-- Image --}}
                                    @if($article->image_path)
                                        <img src="{{ asset('storage/' . $article->image_path) }}"
                                             alt="{{ $article->title }}"
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400">Pas d'image</span>
                                        </div>
                                    @endif

                                    {{-- Contenu --}}
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg mb-2">
                                            <a href="{{ route('articles.show', $article) }}"
                                               class="hover:text-blue-600">
                                                {{ $article->title }}
                                            </a>
                                        </h3>

                                        <p class="text-sm text-gray-600 mb-3">
                                            {{ Str::limit(strip_tags($article->content), 100) }}
                                        </p>

                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>Par {{ $article->user->name }}</span>
                                            <span>{{ $article->comments_count }} commentaire(s)</span>
                                        </div>

                                        <div class="mt-2">
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                {{ $article->category->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <p class="text-gray-600">Aucun article pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
