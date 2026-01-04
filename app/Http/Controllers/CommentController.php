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
        // validation
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // commentaires fait
        Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'article_id' => $article->id,
        ]);
        return back()->with('success', 'Commentaire ajouté avec succès !');

    }

    /**
     * Mettre à jour un commentaire (son auteur uniquement)
     */
    public function update(Request $request, Comment $comment)
    {
        // vérifier si l'utilisateur est l'auteur
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce commentaire.');
        }

        // validation
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // maj commentaire
        $comment->update([
            'content' => $request->content,
        ]);
        return redirect()->route('articles.show', $comment->article_id)
            ->with('success', 'Commentaire modifié avec succès.');

    }

    /**
     * Supprimer un commentaire (son auteur uniquement)
     */
    public function destroy(Comment $comment)
    {
        // vérifier si l'utilisateur est l'auteur
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }

        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');

    }
}
