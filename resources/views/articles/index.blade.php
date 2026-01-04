<x-app-layout>
    {{-- Récupération des filtres actifs depuis l'URL --}}
    @php
        $activeCategories = request()->input('categories', []);
        $activeTags = request()->input('tags', []);
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(empty($activeCategories) && empty($activeTags))
                {{ __('Tous les articles') }}
            @else
                Filtres actifs :
                @if(!empty($activeCategories))
                    <span class="text-blue-600 mr-2">Catégories({{ count($activeCategories) }})</span>
                @endif
                @if(!empty($activeTags))
                    <span class="text-blue-600">Tags({{ count($activeTags) }})</span>
                @endif
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

                {{-- SIDEBAR : FILTRES MULTIPLES --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="font-bold text-xl text-gray-800">Filtres</h3>
                        @if(!empty($activeCategories) || !empty($activeTags))
                            <a href="{{ route('home') }}" class="text-xs text-red-500 hover:text-red-700 underline">Tout effacer</a>
                        @endif
                    </div>

                    {{-- 1. Liste des Catégories --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3 uppercase text-xs tracking-wider">Par Catégories</h4>
                        <ul class="space-y-2">
                            @foreach($categories as $cat)
                                @php
                                    // Logique pour Ajouter / Enlever la catégorie
                                    $isActive = in_array($cat->slug, $activeCategories);
                                    $newCategories = $isActive
                                        ? array_filter($activeCategories, fn($c) => $c !== $cat->slug) // Retirer
                                        : array_merge($activeCategories, [$cat->slug]); // Ajouter

                                    // Génération de l'URL
                                    $url = route('home', array_merge(request()->except('categories', 'page'), ['categories' => $newCategories]));
                                @endphp

                                <li>
                                    <a href="{{ $url }}"
                                       class="flex items-center text-sm p-2 rounded transition cursor-pointer
                                       {{ $isActive ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">

                                        {{-- Case à cocher visuelle --}}
                                        <div class="w-4 h-4 border rounded mr-2 flex items-center justify-center {{ $isActive ? 'bg-blue-600 border-blue-600' : 'border-gray-300 bg-white' }}">
                                            @if($isActive)
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </div>

                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- 2. Nuage de Tags --}}
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3 uppercase text-xs tracking-wider">Par Tags</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $pTag)
                                @php
                                    // Logique pour Ajouter / Enlever le tag
                                    $isTagActive = in_array($pTag->slug, $activeTags);
                                    $newTags = $isTagActive
                                        ? array_filter($activeTags, fn($t) => $t !== $pTag->slug)
                                        : array_merge($activeTags, [$pTag->slug]);

                                    $tagUrl = route('home', array_merge(request()->except('tags', 'page'), ['tags' => $newTags]));
                                @endphp

                                <a href="{{ $tagUrl }}"
                                   class="text-xs inline-flex items-center px-3 py-1 rounded-full border transition cursor-pointer select-none
                                   {{ $isTagActive
                                        ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                                        : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }}">
                                    @if($isTagActive)
                                        <span class="mr-1">✕</span>
                                    @else
                                        <span class="mr-1">#</span>
                                    @endif
                                    {{ $pTag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- LISTE DES ARTICLES --}}
                <div class="md:col-span-3">
                    @if($articles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($articles as $article)
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full hover:shadow-md transition-shadow duration-300">
                                    {{-- Image --}}
                                    <a href="{{ route('articles.show', $article) }}" class="block overflow-hidden h-48">
                                        @if($article->image_path)
                                            <img src="{{ asset('storage/' . $article->image_path) }}"
                                                 alt="{{ $article->title }}"
                                                 class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Contenu --}}
                                    <div class="p-5 flex flex-col flex-grow">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">
                                                {{ $article->category->name }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ $article->created_at->format('d M Y') }}
                                            </span>
                                        </div>

                                        <h3 class="font-bold text-lg mb-2 leading-tight text-gray-900">
                                            <a href="{{ route('articles.show', $article) }}" class="hover:text-blue-600 transition">
                                                {{ $article->title }}
                                            </a>
                                        </h3>

                                        <p class="text-sm text-gray-600 mb-4 flex-grow">
                                            {{ Str::limit(strip_tags($article->content), 100) }}
                                        </p>

                                        @if($article->tags->count() > 0)
                                            <div class="flex flex-wrap gap-1 mb-3">
                                                @foreach($article->tags->take(3) as $t)
                                                    <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">#{{ $t->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-xs text-gray-500 mt-auto">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-2">
                                                    {{ substr($article->user->name, 0, 1) }}
                                                </div>
                                                <span>{{ $article->user->name }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                                {{ $article->comments_count }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center col-span-1 md:col-span-2 lg:col-span-3">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Oups, aucun résultat !</h3>
                            <p class="text-gray-500 mt-2">Aucun article ne correspond à cette combinaison de filtres.</p>
                            <div class="mt-6">
                                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                    Réinitialiser les filtres
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
