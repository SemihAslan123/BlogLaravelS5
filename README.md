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

# Mise en production
npm run build

# Supprime le cache des routes
php artisan route:clear

# Vide le cache des templates Blade compilés
php artisan view:clear

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
