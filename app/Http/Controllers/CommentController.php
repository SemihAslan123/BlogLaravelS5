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

    }

    /**
     * Mettre à jour un commentaire
     */
    public function update(Request $request, Comment $comment)
    {

    }

    /**
     * Supprimer un commentaire (son auteur uniquement)
     */
    public function destroy(Comment $comment)
    {
        
    }
}
