<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Page d'accueil - Liste de tous les articles
     */
    public function index()
    {
        $articles = Article::with(['user', 'category', 'tags'])
            ->withCount('comments')
            ->latest()
            ->paginate(12);

        $categories = Category::all();
        $popularTags = Tag::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(10)
            ->get();

        return view('articles.index', compact('articles', 'categories', 'popularTags'));
    }

    /**
     * Afficher un article en détail
     */
    public function show(Article $article)
    {
        // Charger l'article avec ses relations
    }

    /**
     * Filtrage articles par catégorie
     */
    public function byCategory(Category $category)
    {

    }

    /**
     * Filtrage articles par tag
     */
    public function byTag(Tag $tag)
    {

    }

    /**
     * Formulaire de création d'article (éditeur uniquement)
     */
    public function create()
    {
        // Vérifier l'autorisation

    }

    /**
     * Enregistrer un nouvel article
     */
    public function store(Request $request)
    {
        // Vérifier l'autorisation
        if (Gate::denies('create-article')) {
            abort(403);
        }

        // Valider les données
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gérer l'upload de l'image

        // Créer l'article

        // Attacher les tags

    }

    /**
     * Formulaire de modification d'article
     */
    public function edit(Article $article)
    {

    }

    /**
     * Mettre à jour un article
     */
    public function update(Request $request, Article $article)
    {
        // Vérifier l'autorisation
    }

    /**
     * Supprimer un article
     */
    public function destroy(Article $article)
    {
        // Vérifier l'autorisation
    }
}
