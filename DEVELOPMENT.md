# Guide de développement - Blog Laravel

## État actuel du projet

### Ce qui fonctionne
- Base de données complète (migrations, modèles, relations)
- Authentification et gestion des rôles (user, editor, admin)
- Redirections après connexion
- Page d'accueil avec liste des articles
- Navigation adaptée selon le rôle

### Ce qui reste à implémenter

#### 1. ArticleController
- [ ] `show()` - Afficher un article en détail
- [ ] `byCategory()` - Filtrer par catégorie
- [ ] `byTag()` - Filtrer par tag
- [ ] `create()` - Formulaire création
- [ ] `store()` - Enregistrer article
- [ ] `edit()` - Formulaire modification
- [ ] `update()` - Mettre à jour article
- [ ] `destroy()` - Supprimer article

#### 2. CommentController
- [ ] `store()` - Ajouter commentaire
- [ ] `update()` - Modifier commentaire
- [ ] `destroy()` - Supprimer commentaire

#### 3. EditorRequestController
- [ ] `store()` - Créer demande éditeur
- [ ] `approve()` - Approuver demande
- [ ] `reject()` - Rejeter demande

#### 4. DashboardController
- [ ] `editorDashboard()` - Dashboard éditeur
- [ ] `adminDashboard()` - Dashboard admin
- [ ] `users()` - Liste utilisateurs
- [ ] `deleteUser()` - Supprimer utilisateur

#### 5. Vues à créer
- [ ] `articles/show.blade.php`
- [ ] `articles/create.blade.php`
- [ ] `articles/edit.blade.php`
- [ ] `dashboard/editor.blade.php`
- [ ] `dashboard/admin.blade.php`
- [ ] `admin/users.blade.php`

## Installation
```bash
# Cloner le repo
git clone [URL]

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate:fresh --seed

# Lien storage pour les images
php artisan storage:link

# Lancer le serveur
php artisan serve
```

## Comptes de test

- **Admin**: admin@blog.com / password
- **Éditeur**: editor@blog.com / password
- **User**: user@blog.com / password

## Ressources

- [Documentation Laravel](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Validation](https://laravel.com/docs/validation)

