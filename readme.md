# 🍴 Atable – Application de recettes

## 📖 Présentation
**Atable** est une application web développée avec **Symfony 7** qui permet de partager et consulter des recettes de cuisine.  
Elle offre un système d’authentification, la gestion des recettes, ainsi que l’interaction entre utilisateurs (commentaires, notes, favoris).

---

## 🚀 Installation

### 1. Cloner le projet
Clonez le dépôt GitHub en SSH :
```bash
git clone git@github.com:Boulex39/atable.git
cd atable
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configurer l’environnement
Copiez le fichier `.env` en `.env.local` et adaptez vos paramètres de base de données :
```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/atable"
```
*(exemple avec MySQL, adaptez selon votre config)*

### 4. 📦 Base de données
Un export SQL est fourni à la racine du projet : **atable.sql**

Créez la base et importez le fichier :
```bash
php bin/console doctrine:database:create
mysql -u root -p atable < atable.sql
```

### 5. Lancer le serveur Symfony
```bash
symfony serve -d
```

Accédez à l’application sur : [http://localhost:8000](http://localhost:8000)

---

## ✅ Utilisation rapide
- **Créer un compte** via l’interface d’inscription  
- **Se connecter** pour accéder aux fonctionnalités (ajout de recettes, commentaires, notes…)  
- **Naviguer** dans la liste des recettes ou en rechercher par catégorie/ingrédient  

---

## ✨ Fonctionnalités principales

### 👤 Utilisateurs
- Création de compte / Connexion / Déconnexion  
- Gestion du profil utilisateur  
- Rôles : `ROLE_USER`, `ROLE_ADMIN` (gestion et modération)

### 🍽️ Recettes
- Création, modification et suppression d’une recette  
- Liste avec pagination  
- Affichage détaillé avec ingrédients, étapes et images  
- Recherche par mot-clé, catégorie ou temps de préparation  

### 💬 Interactions sociales
- Commentaires sur les recettes  
- Système de notes (1 à 5 étoiles)  
- Classement des recettes les mieux notées  
- Ajout aux favoris (sauvegarde personnelle)

---

## 📂 Structure du projet
- `src/` → Code source Symfony (entités, contrôleurs, formulaires, services)  
- `templates/` → Vues Twig  
- `public/` → Fichiers publics (assets, uploads, index.php)  
- `migrations/` → Migrations Doctrine  
- `atable.sql` → Export SQL de la base de données  

---

## 👨‍💻 Auteur
Projet réalisé par **Boulex39** dans le cadre d’un projet de fin d’année (Symfony 7).

