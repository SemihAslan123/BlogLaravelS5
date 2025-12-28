<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Article;
use App\Models\EditorRequest;
use App\Models\User;
use App\Models\Comment;

class DashboardController extends Controller
{
    /**
     * Dashboard pour éditeur
     */
    public function editorDashboard()
    {
        // Vérifier que l'utilisateur est bien éditeur
        if (Gate::denies('is-editor')) {
            abort(403, 'Accès non autorisé');
        }

        $user = Auth::user();

        // Ses articles avec catégorie et nombre de commentaires
        $articles = $user->articles()
            ->with(['category', 'comments'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        // Statistiques
        $totalArticles = $user->articles()->count();
        $totalComments = Comment::whereIn('article_id', $user->articles->pluck('id'))->count();

        return view('dashboard.editor', compact('articles', 'totalArticles', 'totalComments'));
    }

    /**
     * Dashboard pour admin
     */
    public function adminDashboard()
    {
        // Vérifier que l'utilisateur est bien admin
        if (Gate::denies('is-admin')) {
            abort(403, 'Accès non autorisé');
        }

        // Demandes en attente
        $pendingRequests = EditorRequest::where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        // Statistiques globales
        $totalUsers = User::where('role', 'user')->count();
        $totalEditors = User::where('role', 'editor')->count();
        $totalArticles = Article::count();
        $totalComments = Comment::count();

        // Articles récents (tous les articles)
        $recentArticles = Article::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'pendingRequests',
            'totalUsers',
            'totalEditors',
            'totalArticles',
            'totalComments',
            'recentArticles'
        ));
    }

    /**
     * Liste des utilisateurs (admin uniquement)
     */
    public function users()
    {
        if (Gate::denies('is-admin')) {
            abort(403);
        }

        $users = User::withCount(['articles', 'comments'])
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Supprimer un utilisateur (admin uniquement)
     */
    public function deleteUser(User $user)
    {
        if (Gate::denies('is-admin')) {
            abort(403);
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "L'utilisateur {$userName} a été supprimé avec succès.");
    }
}
