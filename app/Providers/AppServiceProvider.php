<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate pour vérifier si l'utilisateur est admin
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate pour vérifier si l'utilisateur est éditeur
        Gate::define('is-editor', function (User $user) {
            return $user->role === 'editor';
        });

        // Gate pour vérifier si l'utilisateur peut créer des articles
        Gate::define('create-article', function (User $user) {
            return $user->role === 'editor';
        });

        // Gate pour vérifier si l'utilisateur peut modifier un article
        Gate::define('update-article', function (User $user, Article $article) {
            return $user->id === $article->user_id && $user->role === 'editor';
        });

        // Gate pour vérifier si l'utilisateur peut supprimer un article
        Gate::define('delete-article', function (User $user, Article $article) {
            return $user->id === $article->user_id && $user->role === 'editor';
        });

        // Gate pour modifier un commentaire
        Gate::define('update-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id;
        });

        // Gate pour supprimer un commentaire
        Gate::define('delete-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id;
        });

    }
}
