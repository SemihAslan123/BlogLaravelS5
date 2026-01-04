<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EditorRequestController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil avec liste des articles
Route::get('/', [ArticleController::class, 'index'])->name('home');

// Afficher un article en détail
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

// Filtrer les articles par catégorie (désactiver pour permettre un filtrage multiple)
//Route::get('/category/{category:slug}', [ArticleController::class, 'byCategory'])->name('articles.by-category');

// Filtrer les articles par tag (désactiver pour permettre un filtrage multiple)
//Route::get('/tag/{tag:slug}', [ArticleController::class, 'byTag'])->name('articles.by-tag');

/*
|--------------------------------------------------------------------------
| Routes utilisateurs connectés
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Profil utilisateur (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Commentaires sur les articles
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', function (Comment $comment) {
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }
        return view('comments.edit', compact('comment'));
    })->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Demande pour devenir éditeur
    Route::post('/editor-request', [EditorRequestController::class, 'store'])->name('editor.request');
});

/*
|--------------------------------------------------------------------------
| Routes pour les éditeurs uniquement
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:is-editor'])->prefix('editor')->name('editor.')->group(function () {

    // Dashboard éditeur
    Route::get('/dashboard', [DashboardController::class, 'editorDashboard'])->name('dashboard');

    // Gestion des articles (CRUD)
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes pour l'admin uniquement
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:is-admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Gestion des demandes d'éditeur
    Route::get('/editor-requests', [EditorRequestController::class, 'index'])->name('editor-requests.index');
    Route::post('/editor-requests/{editorRequest}/approve', [EditorRequestController::class, 'approve'])->name('editor-requests.approve');
    Route::post('/editor-requests/{editorRequest}/reject', [EditorRequestController::class, 'reject'])->name('editor-requests.reject');

    // Gestion des utilisateurs
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::delete('/users/{user}', [DashboardController::class, 'deleteUser'])->name('users.destroy');
});

//Routes d'authentification (Breeze)
require __DIR__.'/auth.php';
