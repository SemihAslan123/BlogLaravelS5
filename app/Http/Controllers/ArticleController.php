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
        $article->load([
            'user',
            'category',
            'tags',
            'comments.user'
        ]);

        return view('articles.show', compact('article'));
    }

    /**
     * Articles par catégorie
     */
    public function byCategory(Category $category)
    {
        $articles = $category->articles()
            ->with(['user', 'category', 'tags'])
            ->withCount('comments')
            ->latest()
            ->paginate(12);

        $categories = Category::all();
        $popularTags = Tag::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(10)
            ->get();

        return view('articles.index', compact('articles', 'categories', 'popularTags', 'category'));
    }

    /**
     * Articles par tag
     */
    public function byTag(Tag $tag)
    {
        $articles = $tag->articles()
            ->with(['user', 'category', 'tags'])
            ->withCount('comments')
            ->latest()
            ->paginate(12);

        $categories = Category::all();
        $popularTags = Tag::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(10)
            ->get();

        return view('articles.index', compact('articles', 'categories', 'popularTags', 'tag'));
    }

    /**
     * Formulaire de création d'article (éditeur uniquement)
     */
    public function create()
    {
        if (Gate::denies('create-article')) {
            abort(403, 'Accès non autorisé');
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('articles.create', compact('categories', 'tags'));
    }

    /**
     * Enregistrer un nouvel article
     */
    public function store(Request $request)
    {
        if (Gate::denies('create-article')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gérer l'upload de l'image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        // Créer l'article
        $article = Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
        ]);

        // Attacher les tags
        if (!empty($validated['tags'])) {
            $article->tags()->attach($validated['tags']);
        }

        return redirect()->route('editor.dashboard')->with('success', 'Article créé avec succès !');
    }

    /**
     * Formulaire de modification d'article
     */
    public function edit(Article $article)
    {
        if (Gate::denies('update-article', $article)) {
            abort(403);
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('articles.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * Mettre à jour un article
     */
    public function update(Request $request, Article $article)
    {
        if (Gate::denies('update-article', $article)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($article->image_path) {
                \Storage::disk('public')->delete($article->image_path);
            }
            $imagePath = $request->file('image')->store('articles', 'public');
            $article->image_path = $imagePath;
        }

        // Mettre à jour l'article
        $article->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
        ]);

        // Synchroniser les tags
        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        } else {
            $article->tags()->detach();
        }

        return redirect()->route('editor.dashboard')->with('success', 'Article modifié avec succès !');
    }

    /**
     * Supprimer un article
     */
    public function destroy(Article $article)
    {
        if (Gate::denies('delete-article', $article)) {
            abort(403);
        }

        // Supprimer l'image si elle existe
        if ($article->image_path) {
            \Storage::disk('public')->delete($article->image_path);
        }

        $article->delete();

        return redirect()->route('editor.dashboard')->with('success', 'Article supprimé avec succès !');
    }
}
