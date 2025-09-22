# EcoRide

Application de covoiturage écologique.

# Prérequis

Avant d’installer le projet, assurez-vous d’avoir :
Git installé → pour cloner le dépôt
XAMPP (ou équivalent LAMP/WAMP) → pour Apache, PHP et MariaDB
Composer → pour gérer les dépendances PHP
Node.js et NPM → pour gérer les dépendances front-end (Bootstrap, etc.)
MongoDB (local ou en cloud) → pour gérer les avis et notes des chauffeurs
site de covoiturage

# installation

1. Cloner le projet
   git clone https://github.com/nelly34800/EcoRide.git
   cd EcoRide
   Permet de récupérer le code source sur votre machine.
2. Installer les dépendances PHP avec Composer
   composer install
3. Installer les dépendances front-end avec NPM
   npm install
4. Configurer l’environnement
   Dupliquer le fichier .env.example et le renommer en .env.
   Modifier les informations de connexion (base de données, identifiants, hôte local).
5. Créer et importer la base de données
   fichier bdd.sql joint
6. Lancer le serveur local
   Démarrer Apache et MySQL dans XAMPP.
   Accéder au projet via http://ecoride.local (ou http://localhost/EcoRide).

# déploiement

Pour l’instant, le projet est prévu pour un déploiement local grâce à XAMPP.
Le déploiement futur pourra se faire sur un hébergeur mutualisé (PHP/MariaDB) ou sur un serveur cloud (AWS, OVH, Azure) avec Apache/Nginx, PHP et MongoDB.
