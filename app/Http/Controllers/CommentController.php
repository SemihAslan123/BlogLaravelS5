<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Ajouter un commentaire sur un article
     */
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ], [
            'content.required' => 'Le commentaire ne peut pas être vide.',
            'content.min' => 'Le commentaire doit contenir au moins 3 caractères.',
            'content.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        Comment::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'article_id' => $article->id,
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès !');
    }

    /**
     * Mettre à jour un commentaire
     */
    public function update(Request $request, Comment $comment)
    {
        // Vérifier que l'utilisateur peut modifier ce commentaire
        if (Gate::denies('update-comment', $comment)) {
            abort(403, 'Vous ne pouvez pas modifier ce commentaire.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ], [
            'content.required' => 'Le commentaire ne peut pas être vide.',
            'content.min' => 'Le commentaire doit contenir au moins 3 caractères.',
            'content.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Commentaire modifié avec succès !');
    }

    /**
     * Supprimer un commentaire (son auteur uniquement)
     */
    public function destroy(Comment $comment)
    {
        // Vérifier que l'utilisateur peut supprimer ce commentaire
        if (Gate::denies('delete-comment', $comment)) {
            abort(403, 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé avec succès !');
    }
}
