Trophée de la générosité — HUG

**Stack technique**

- **Back-end** : Laravel 12 (PHP 8.3+)
- **Front-end** : Vue 3 + Vue Router + Pinia + TailwindCSS
- **Base de données** : MySQL
- **Build** : Vite
- **Hébergement** : Infomaniak

**Installation local**

```bash
git clone <url-du-repo>
cd ProjInt

cp .env.example .env
composer install
npm install
php artisan key:generate

**Config MySQL**

1. Lancer serveur MySQL local (MAMP, WAMP, Herd, etc)
2. Créer une db nommée `trophee_hug`
3. Remplir les variables dans `.env` :
    DB_HOST=127.0.0.1
    DB_PORT=3306 par défaut, 8889 si MAMP
    DB_DATABASE=trophee_hug
    DB_USERNAME=root
    DB_PASSWORD="root" si MAMP, vide sinon
4. Lancer les migrations
