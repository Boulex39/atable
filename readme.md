# 🍴 À Table !– Application de recettes

## 📖 Présentation
**À Table** est une application web développée avec **Symfony 7** permettant de **partager, consulter et noter des recettes de cuisine**.  
L’objectif est de proposer une plateforme moderne, intuitive et responsive où les utilisateurs peuvent créer un compte, publier leurs propres recettes et interagir entre eux.

---

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone git@github.com:Boulex39/atable.git
cd atable
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configurer l’environnement
Copiez le fichier `.env` en `.env.local` puis modifiez vos paramètres de base de données :
```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/atable"
```

### 4. 📦 Base de données
Un export SQL complet est inclus à la racine du projet : **atable.sql**

Créez la base et importez-la :
```bash
php bin/console doctrine:database:create
mysql -u root -p atable < atable.sql
```

### 5. Lancer le serveur Symfony
Deux options possibles :

#### 🟢 Option 1 — Lancer en arrière-plan
```bash
symfony serve -d
```
> Cette commande démarre le serveur **en tâche de fond** (mode détaché).  
> Vous pouvez ensuite exécuter d’autres commandes dans le même terminal.

#### 🟡 Option 2 — Lancer au premier plan
```bash
symfony serve
```
> Cette commande garde le serveur **actif dans le terminal**.  
> Si vous utilisez cette option, vous devrez **ouvrir un nouveau terminal**  
> pour exécuter d’autres commandes (par exemple : `php bin/console`, `composer`, etc.).

Accédez à l’application sur :  
👉 [http://localhost:8000](http://localhost:8000)

---

## ✅ Utilisation rapide
- **Créer un compte** via l’interface d’inscription  
- **Se connecter** pour accéder aux fonctionnalités membres  
- **Ajouter, modifier ou supprimer** vos recettes  
- **Noter et commenter** les recettes des autres utilisateurs  
- **Rechercher** des recettes par mots-clés ou catégories  

---

## ✨ Fonctionnalités principales

### 👤 Utilisateurs
- Inscription / Connexion / Déconnexion  
- Page profil avec informations et recettes publiées  
- Gestion des rôles :  
  - **ROLE_USER** → Membre classique  
  - **ROLE_ADMIN** → Accès à la gestion du contenu et des utilisateurs

### 🍽️ Recettes
- CRUD complet : création, édition, suppression  
- Upload d’image  
- Catégorisation par thème  
- Description, ingrédients, étapes et durées  
- Affichage en **responsive** avec aperçu visuel  
- Page de détail avec notation et commentaires

### 💬 Interactions
- Ajout de **commentaires** sous les recettes  
- Système de **notation (1 à 5 étoiles)**  
- Calcul automatique de la note moyenne  

---

## 📂 Structure du projet
```bash
atable/
├── migrations/          # Migrations Doctrine
├── public/              # Assets publics (images, CSS, index.php)
├── src/                 # Code source Symfony (contrôleurs, entités, formulaires)
├── templates/           # Vues Twig
├── atable.sql           # Export complet de la base de données
└── README.md
```

---

## 🧰 Technologies utilisées
- **Symfony 7** (PHP 8.2)  
- **Doctrine ORM** (MySQL 9.1)  
- **Twig** pour les templates  
- **CSS personnalisé / responsive**  
- **WAMP / Composer / Git / GitHub**

---

## 👨‍💻 Auteur
Projet développé par **BoutsyharatAlexandre**  
💡 Réalisé dans le cadre du **projet de fin de formation – Développeur Web & Web Mobile**.

