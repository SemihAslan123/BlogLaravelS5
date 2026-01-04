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
        // récupère les articles de l'utilisateur connecté, triés par le plus récent
        $articles = Auth::user()->articles()->latest()->get();
        return view('dashboard.editor', compact('articles'));
    }

    /**
     * Dashboard pour admin
     */
    public function adminDashboard()
    {
        // récupère uniquement les demandes en attente ('pending')
        $requests = EditorRequest::where('status', 'pending')->with('user')->get();
        return view('dashboard.admin', compact('requests'));
    }

    /**
     * Liste des utilisateurs (admin uniquement)
     */
    public function users()
    {
        // récupère tout les utilisateurs
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    /**
     * Supprimer un utilisateur (admin uniquement)
     */
    public function deleteUser(User $user)
    {
        // éviter que l'admin se supprimer lui-même par sécurité
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
