<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Éditeur') }}
            </h2>
            {{-- Bouton corrigé avec les classes standard --}}
            <a href="{{ route('editor.articles.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                + Nouvel Article
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mes Articles</h3>

                @if($articles->isEmpty())
                    <p class="text-gray-500">Vous n'avez pas encore publié d'articles.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titre</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium">{{ $article->title }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $article->created_at->format('d/m/Y') }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <div class="flex gap-4">
                                            <a href="{{ route('editor.articles.edit', $article) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Modifier</a>
                                            <form action="{{ route('editor.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
