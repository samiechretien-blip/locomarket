# LocoMarket — Commerce local simplifié

Application web full-stack permettant à des commerçants locaux de publier un catalogue de produits, et aux habitants du quartier de parcourir ce catalogue, composer un panier et passer commande.

Projet réalisé dans le cadre du cours **Développement Web — Niveau Approfondi** (React + Laravel).

## Technologies

- **Front-end** : React (Vite), React Router, Axios
- **Back-end** : Laravel 12, Laravel Sanctum (authentification par token)
- **Base de données** : SQLite

## Fonctionnalités

- Catalogue de produits avec filtrage par catégorie et recherche
- Fiche détail produit
- Panier (ajout, modification de quantité, suppression)
- Authentification (inscription / connexion)
- Passage de commande + historique "Mes commandes"
- Espace d'administration : gestion des produits (CRUD, upload d'images) et des catégories
- Tests backend (PHPUnit) sur les produits et les commandes

## Installation

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Compte admin de démonstration : `admin@locomarket.test` / `password`

### Frontend

```bash
cd frontend
npm install
npm run dev
```

L'application est accessible sur `http://localhost:5173` (le backend doit tourner sur `http://127.0.0.1:8000`).

## Tests

```bash
cd backend
php artisan test
```

## Structure du dépôt

```
locomarket/
├── backend/    # API Laravel (routes, contrôleurs, modèles, migrations, tests)
└── frontend/   # Application React (pages, composants, contexts)
```