<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $article->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Image de couverture --}}
                @if($article->image_path)
                    <div class="w-full h-96 overflow-hidden">
                        <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 text-gray-900">
                    {{-- Infos Article --}}
                    <div class="flex justify-between items-center text-sm text-gray-500 mb-6 border-b pb-4">
                        <span>Par <span class="font-bold text-gray-700">{{ $article->user->name }}</span> le {{ $article->created_at->format('d/m/Y') }}</span>
                        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-semibold">{{ $article->category->name }}</span>
                    </div>

                    {{-- Contenu Article --}}
                    <div class="prose max-w-none mb-6">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    {{-- Tags --}}
                    <div class="mt-8 pt-4 border-t">
                        <h4 class="text-sm font-bold text-gray-700 mb-2">Tags associés :</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($article->tags as $tag)
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Section Commentaires --}}
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Commentaires ({{ $article->comments->count() }})</h3>

                    {{-- Liste des commentaires --}}
                    @forelse($article->comments as $comment)
                        <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-sm text-gray-800">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>

                                {{-- Actions : Modifier / Supprimer (Visible seulement pour l'auteur) --}}
                                @if(Auth::id() === $comment->user_id)
                                    <div class="flex items-center gap-2">
                                        {{-- Bouton Modifier --}}
                                        <a href="{{ route('comments.edit', $comment) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">
                                            Modifier
                                        </a>

                                        {{-- Bouton Supprimer --}}
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <p class="text-gray-700 text-sm mt-2 whitespace-pre-wrap">{{ $comment->content }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">Aucun commentaire pour le moment.</p>
                    @endforelse

                    {{-- Formulaire d'ajout --}}
                    @auth
                        <form action="{{ route('comments.store', $article) }}" method="POST" class="mt-6">
                            @csrf
                            <div class="mb-2">
                                <label for="content" class="sr-only">Votre commentaire</label>
                                <textarea name="content" id="content" rows="3"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Écrire un commentaire..." required></textarea>
                            </div>
                            <div class="text-right">
                                <x-primary-button>Envoyer</x-primary-button>
                            </div>
                        </form>
                    @else
                        <div class="mt-6 p-4 bg-blue-50 text-blue-700 rounded-md text-sm text-center">
                            <a href="{{ route('login') }}" class="font-bold hover:underline">Connectez-vous</a> pour participer à la discussion.
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
