<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages de succès/erreur --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Statistiques globales --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Utilisateurs</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Éditeurs</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $totalEditors }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Articles</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ $totalArticles }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Commentaires</h3>
                        <p class="text-3xl font-bold text-orange-600">{{ $totalComments }}</p>
                    </div>
                </div>
            </div>

            {{-- Demandes d'éditeur en attente --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">
                        Demandes d'éditeur en attente
                        @if($pendingRequests->count() > 0)
                            <span class="ml-2 px-2 py-1 text-xs bg-red-500 text-white rounded-full">
                                {{ $pendingRequests->count() }}
                            </span>
                        @endif
                    </h3>

                    @forelse($pendingRequests as $request)
                        <div class="border-b border-gray-200 py-4 last:border-b-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $request->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $request->user->email }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Demande envoyée le {{ $request->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    {{-- Approuver --}}
                                    <form action="{{ route('admin.editor-requests.approve', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 bg-green-500 text-green text-sm rounded hover:bg-green-600">
                                            Approuver
                                        </button>
                                    </form>
                                    {{-- Rejeter --}}
                                    <form action="{{ route('admin.editor-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 bg-red-500 text-red text-sm rounded hover:bg-red-600">
                                            Rejeter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">Aucune demande en attente.</p>
                    @endforelse
                </div>
            </div>

            {{-- Articles récents --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">Articles récents</h3>

                    @forelse($recentArticles as $article)
                        <div class="border-b border-gray-200 py-3 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-800">
                                        <a href="{{ route('articles.show', $article) }}" class="hover:text-blue-600">
                                            {{ $article->title }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Par {{ $article->user->name }} |
                                        {{ $article->category->name }} |
                                        {{ $article->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">Aucun article pour le moment.</p>
                    @endforelse
                </div>
            </div>

            {{-- Lien vers gestion des utilisateurs --}}
            <div>
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Gérer les utilisateurs
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
