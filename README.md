# Trophée de la générosité — HUG

Application web de gestion des collectes de don du sang en entreprise pour les **Hôpitaux Universitaires de Genève (HUG) / Centre de Transfusion Sanguine (CTS)**.

Site web de démonstration disponible à ces adresses :

https://dondusang.loannjuillerat.ch

https://dondusang.loannjuillerat.ch/entreprise/ubs (espace cobrandé)

> ProjInt — HEIG-VD, juin 2026

---

## Licence

Ce projet est distribué sous licence **MIT** — voir le fichier [`LICENSE`](./LICENSE).

---

## Stack technique

| Couche | Technologie | Version |
|---|---|---|
| Back-end | Laravel | 12 |
| Langage | PHP | 8.2+ |
| Authentification | Laravel Sanctum | token Bearer |
| Base de données | MySQL | 8+ |
| Front-end | Vue 3 | Composition API `<script setup>` |
| Routeur | Vue Router | 4 |
| State management | Pinia | — |
| CSS | TailwindCSS | 4 |
| Build | Vite + laravel-vite-plugin | 8 |
| Police | Instrument Sans | via Bunny Fonts |
| Hébergement | Infomaniak | SSH/FTP |

---

## Prérequis

- PHP 8.2+
- Composer
- Node.js 18+ et npm
- MySQL (local : MAMP, WAMP, Herd, Laragon…)
- Git

---

## Installation locale

### 1. Cloner le dépôt

```bash
git clone <url-du-repo>
cd ProjInt
```

### 2. Variables d'environnement

```bash
cp .env.example .env
```

Modifier `.env` selon votre setup local :

```env
APP_NAME="Trophée de la générosité"
APP_URL=http://localhost:8000

DB_HOST=127.0.0.1
DB_PORT=3306          # 8889 si MAMP
DB_DATABASE=trophee_hug
DB_USERNAME=root
DB_PASSWORD=          # "root" si MAMP, vide sinon

SESSION_DRIVER=database
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8000
```

### 3. Installer les dépendances

```bash
composer install
npm install
```

### 4. Générer la clé applicative

```bash
php artisan key:generate
```

### 5. Créer la base de données

Dans votre client MySQL (phpMyAdmin, TablePlus, CLI…), créer une base nommée `trophee_hug`.

Si MAMP :
```bash
/Applications/MAMP/Library/bin/mysql -u root -proot -P 8889 -e "CREATE DATABASE trophee_hug;"
```

### 6. Migrations et données de test

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 7. Lancer le projet

```bash
# Dans deux terminaux séparés :
php artisan serve --host=127.0.0.1 --port=8000
npm run dev
```

L'application est accessible sur `http://localhost:8000`.

---

## Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Admin HUG | admin@cts-hug.ch | password |
| Coordinateur Pictet | sandra.martin@pictet.com | password |
| Coordinateur Rolex | marc.dubois@rolex.com | password |
| Coordinateur UBS | julie.favre@ubs.com | password |

---

## Structure du projet

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # login, logout, me, register
│   │   │   ├── EntrepriseController.php    # show (slug), collectes, index
│   │   │   ├── CollecteController.php      # incrementInscrits, quizResult
│   │   │   ├── AdminController.php         # stats, entreprises (admin)
│   │   │   ├── AdminCollecteController.php # CRUD collectes (admin)
│   │   │   ├── AdminEntrepriseController.php
│   │   │   ├── AdminCoordinateurController.php
│   │   │   ├── AdminLabelController.php
│   │   │   └── AdminTropheeController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php         # vérifie role = admin
│   │       └── CoordinateurMiddleware.php  # vérifie role = coordinateur
│   └── Models/
│       ├── User.php          # HasApiTokens, role, isAdmin(), isCoordinateur()
│       ├── Entreprise.php    # slug, logo, couleur_primaire, collectes(), label(), trophees()
│       ├── Collecte.php      # statut enum, active flag, nb_inscrits_estime
│       ├── Admin.php
│       ├── Coordinateur.php  # user_id, entreprise_id, telephone, poste
│       ├── Label.php         # date_expiration, actif, estValide()
│       ├── Trophee.php       # annee, commentaire
│       └── QuizResult.php    # collecte_id, eligible (boolean nullable)
│
├── database/
│   ├── migrations/           # toutes les migrations (ordre chronologique)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php        # admin + 3 coordinateurs
│       ├── EntrepriseSeeder.php  # 10 entreprises avec logos
│       ├── CollecteSeeder.php    # collectes de test (actives + passées)
│       ├── LabelSeeder.php
│       ├── TropheeSeeder.php
│       └── QuizResultSeeder.php
│
├── resources/
│   ├── css/
│   │   └── app.css           # variables CSS globales, classes utilitaires (.btn, .badge, .tip-card…)
│   ├── js/
│   │   ├── app.js            # point d'entrée Vue, fetchMe + guards avant mount
│   │   ├── App.vue           # <RouterView />
│   │   ├── routes.js         # toutes les routes + guards par rôle
│   │   ├── stores/
│   │   │   ├── auth.js       # token, role, entrepriseSlug, fetchMe, isAdmin, isCoordinateur
│   │   │   └── cobrand.js    # couleurPrimaire, logo, nom, slug, textOnBrand (luminance)
│   │   ├── components/
│   │   │   ├── AppNavbar.vue       # navbar unifiée (détecte auto mode cobrandé)
│   │   │   ├── QuizNavbar.vue      # navbar spécifique au quiz
│   │   │   ├── Footer.vue          # footer avec props slug + compact
│   │   │   ├── Quiz.vue            # composant quiz (legacy, non utilisé)
│   │   │   ├── QuizResult.vue      # composant résultat (legacy, non utilisé)
│   │   │   ├── ReservationCTA.vue  # bouton CTA réservation
│   │   │   └── CollecteDetails.vue # orphelin (gardé volontairement)
│   │   ├── layouts/
│   │   │   ├── AdminLayout.vue     # sidebar adaptative admin/coordinateur
│   │   │   └── CoordLayout.vue     # layout espace coordinateur co-brandé
│   │   └── pages/
│   │       ├── Home.vue                          # page d'accueil publique
│   │       ├── Login.vue                         # connexion + redirect par rôle
│   │       ├── Entreprise.vue                    # landing co-brandée (/entreprise/:slug)
│   │       ├── CoEspaceEntreprise.vue            # espace public entreprise (collectes)
│   │       ├── CoNouvelleCollecte.vue            # création/édition collecte (coordinateur)
│   │       ├── QuizPage.vue                      # quiz complet (9 questions, 3 résultats)
│   │       ├── InscriptionCollecte.vue           # formulaire inscription don
│   │       ├── Label.vue / Trophee.vue           # pages informatives
│   │       ├── FAQ.vue / Contact.vue             # pages statiques
│   │       ├── PolitiqueConfidentalite.vue
│   │       ├── ConditionsGenerales.vue
│   │       ├── admin/
│   │       │   ├── Dashboard.vue
│   │       │   ├── collectes/   Index.vue · Create.vue · Edit.vue
│   │       │   ├── coordinateurs/ Index.vue · Create.vue · Edit.vue
│   │       │   ├── labels/      Index.vue
│   │       │   └── trophees/    Index.vue
│   │       └── coordinateurs/
│   │           └── Dashboard.vue
│   └── views/
│       └── app.blade.php     # unique vue Blade — point d'entrée SPA
│
├── public/
│   ├── images/
│   │   ├── courage/          # mascottes du quiz (.png)
│   │   │   ├── Mascotte_default.png
│   │   │   ├── Mascotte_award.png       # résultat éligible
│   │   │   ├── Mascotte_failure.png     # résultat non-éligible
│   │   │   ├── Mascotte_insight.png     # insight Q0, Q3, Q6
│   │   │   ├── Mascotte_glass 1.png     # insight Q1, Q4, Q7
│   │   │   └── Mascotte_think 2.png     # insight Q2, Q5, Q8
│   │   ├── marquee/          # logos partenaires (.png)
│   │   │   ├── logo_UBS.png · logo_Pictet.png · logo_Lombard.png
│   │   │   ├── logo_Logitech.png · logo_Patek.png · logo_bcge.png
│   │   │   ├── logo_Genevatrading.png · logo_SIG.png · rolex-logo.png
│   │   │   ├── La-Vie-Black-Logo-1.png · Logo_Nestle.svg.png
│   │   │   └── (ajouter ici les nouveaux logos)
│   │   ├── Icons/            # icônes SVG du quiz
│   │   └── *.webp / *.png    # images hero, thumbnails, logo HUG
│   └── build/                # assets compilés par Vite (ne pas éditer)
│
├── routes/
│   ├── api.php               # toutes les routes API /api/
│   └── web.php               # catch-all → SPA Vue
│
├── .env                      # variables locales (non commité)
├── .env.example              # template à copier
├── .github/workflows/        # CI/CD GitHub Actions
├── composer.json             # dépendances PHP
├── package.json              # dépendances JS
└── vite.config.js            # config Vite + laravel-vite-plugin
```

---

## Routes API

| Méthode | URL | Auth | Description |
|---|---|---|---|
| POST | `/api/login` | — | Retourne `{ token, role }` |
| POST | `/api/logout` | Bearer | Révoque le token |
| GET | `/api/me` | Bearer | Retourne `{ user, role }` |
| POST | `/api/register` | — | Inscription coordinateur + entreprise |
| GET | `/api/entreprises` | — | Liste des entreprises |
| GET | `/api/entreprises/{slug}` | — | Détail entreprise + collecte active + label |
| GET | `/api/entreprises/{slug}/collectes` | — | Toutes les collectes d'une entreprise |
| POST | `/api/collectes/{collecte}/nb_inscrits_estime` | — | Incrémente le compteur |
| POST | `/api/collectes/{collecte}/quiz-result` | — | Enregistre un résultat de quiz |
| GET | `/api/coordinateur/collectes` | Bearer (coord) | Collectes de l'entreprise du coordinateur |
| POST | `/api/coordinateur/collectes` | Bearer (coord) | Créer une collecte |
| PUT | `/api/coordinateur/collectes/{id}` | Bearer (coord) | Modifier une collecte |
| POST | `/api/coordinateur/collectes/{id}/annuler` | Bearer (coord) | Annuler une collecte |
| GET/POST/PUT/DELETE | `/api/admin/...` | Bearer (admin) | CRUD complet admin |

---

## Base de données

| Table | Description |
|---|---|
| `users` | id, name, email, password, role (admin/coordinateur) |
| `admins` | id, user_id |
| `coordinateurs` | id, user_id, entreprise_id, telephone, poste |
| `entreprises` | id, nom, slug, logo, couleur_primaire, nb_employes, domaine, adresse, ville, npa, parent_id |
| `collectes` | id, entreprise_id, admin_id, coordinateur_id, titre, date_debut, date_fin, lieu, sur_site, horaires, objectif_dons, active, statut, nb_inscrits_estime, nb_dons_realises |
| `labels` | id, entreprise_id, date_attribution, date_expiration, actif |
| `trophees` | id, entreprise_id, admin_id, annee, commentaire |
| `quiz_results` | id, collecte_id, eligible (boolean nullable) |

---

## Marches à suivre courantes

### Ajouter un logo partenaire

1. Déposer le fichier dans `public/images/marquee/`
2. Dans `database/seeders/EntrepriseSeeder.php`, ajouter l'entrée ou mettre à jour le champ `logo` :
   ```php
   'logo' => '/images/marquee/nom-du-logo.png',
   ```
3. Dans `resources/js/pages/Home.vue`, ajouter l'image dans **les deux** `marquee-group` :
   ```html
   <img :src="'/images/marquee/nom-du-logo.png'" class="img-marquee" alt="Nom entreprise">
   ```
4. Commiter, pousser, et sur le serveur : `git pull` + `php artisan db:seed`

> ⚠️ Utiliser toujours `:src="'...'"` (binding Vue) et non `src="..."` — Vite essaie sinon de résoudre le chemin comme un import et plante au build.

### Ajouter une nouvelle mascotte au quiz

1. Déposer le fichier `.png` dans `public/images/courage/`
2. Dans `resources/js/pages/QuizPage.vue`, ajouter le chemin dans le tableau `insightMascottes` :
   ```js
   const insightMascottes = [
       '/images/courage/Mascotte_insight.png',
       '/images/courage/Mascotte_glass 1.png',
       '/images/courage/Mascotte_think 2.png',
       '/images/courage/Mascotte_nouveau.png', // ← ici
   ];
   ```
   La rotation se fait automatiquement par `currentQ.value % insightMascottes.length`.

### Ajouter une entreprise en base (sans reseed complet)

```bash
php artisan db:seed --class=EntrepriseSeeder
```
> Ne fonctionne que si l'entreprise n'existe pas déjà (pas d'upsert — adapter le seeder si besoin).

### Reset complet de la base

```bash
php artisan migrate:fresh --seed
```
> ⚠️ Efface toutes les données. À ne faire que sur un environnement de développement ou de recette.

### Builder pour la production

```bash
npm run build
```
Les fichiers compilés atterrissent dans `public/build/` — ne pas les éditer directement.

### Vider les caches Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:clear
```

---

## Déploiement (Infomaniak SSH)

```bash
# 1. Connexion
ssh user@xxx.ftp.infomaniak.com

# 2. Dossier projet
cd /chemin/du/projet

# 3. Récupérer le code
git pull

# 4. Dépendances PHP (prod, sans dev)
composer install --no-dev --optimize-autoloader

# 5. Build assets
npm ci && npm run build

# 6. Migrations (sans reset)
php artisan migrate --force

# 7. Seeders si besoin
php artisan db:seed --class=EntrepriseSeeder

# 8. Lien storage (une seule fois)
php artisan storage:link

# 9. Caches
php artisan config:cache
php artisan route:cache
```

Pour un reset complet en recette :
```bash
php artisan migrate:fresh --seed
```

### Accès base de données distante

Via tunnel SSH (TablePlus, DBeaver, etc.) :
```bash
ssh -L 3307:127.0.0.1:3306 user@xxx.ftp.infomaniak.com
# Puis connecter un client MySQL sur 127.0.0.1:3307
```

---

## Variables CSS globales (app.css)

```css
--color-default-red          /* rouge HUG */
--color-default-blue-59      /* bleu */
--color-default-green        /* vert */
--color-default-green-79     /* vert clair (fond tip-card) */
--default-titles             /* couleur texte titres */
--default-text               /* couleur texte courant */
--light-grey                 /* fond clair */
--white
```

---

## Notes importantes

- **`.env` n'est jamais commité** — chaque développeur a son propre fichier local.
- **`public/build/`** est généré par `npm run build` — ne pas commiter ces fichiers si possible (voir `.gitignore`).
- **`storage/`** : les fichiers uploadés (logos via formulaire) vont dans `storage/app/public/logos/` et sont servis via le symlink `public/storage/`. Les images statiques du projet sont dans `public/images/` et sont commitées.
- **Sanctum** : l'auth utilise des tokens Bearer, pas des cookies de session. Le token est stocké dans le store Pinia côté front et envoyé en header `Authorization: Bearer <token>`.
