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

    }

    /**
     * Dashboard pour admin
     */
    public function adminDashboard()
    {

    }

    /**
     * Liste des utilisateurs (admin uniquement)
     */
    public function users()
    {

    }

    /**
     * Supprimer un utilisateur (admin uniquement)
     */
    public function deleteUser(User $user)
    {
        
    }
}
