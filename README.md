# 🎵 ConcertCM+ — Plateforme Unifiée d'Événements & Billetterie

[![Version Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![Version PHP](https://img.shields.io/badge/PHP-8.4%2B-blue.svg?style=flat-square&logo=php)](https://php.net)
[![Système Esthétique](https://img.shields.io/badge/Design_System-Terrain_Vivant-green.svg?style=flat-square)](#🎨-design--système-esthétique)
[![Base de Données](https://img.shields.io/badge/Base_de_Données-MySQL%20%2F%20MariaDB-orange.svg?style=flat-square&logo=mysql)](https://mysql.com)

**ConcertCM+** est une application web de nouvelle génération conçue pour gérer, vendre et vérifier des billets et des ressources d'événements au sein d'un écosystème unifié unique. Elle dispose d'une **architecture hybride** unique qui gère à la fois les événements musicaux spécifiques (concerts, billetterie, vérification QR) et les expositions génériques (foires, salons, stands et réservation de ressources physiques).

---

## 🌟 Fonctionnalités Clés

### 🎫 Spectateur (Utilisateur Simple)
* **Réservation Instantanée** : Tunnel de réservation simulé et direct pour les billets de concert et les entrées d'événement.
* **Dashboard Intelligent** : Espace utilisateur complet affichant les billets actifs, les réservations de stands, les statistiques (total dépensé, billets valides, espaces actifs) et un historique complet des réservations expirées.
* **Protection des Billets** : Options d'annulation flexibles appliquant une contrainte stricte de **2 jours maximum** avant le début de l'événement.
* **Contrôle d'Accès** : Codes QR et codes de référence uniques et sécurisés pour chaque billet.
* **Section Découverte** : Système de recommandation adaptatif mettant en valeur les prochains événements à l'affiche au Cameroun.

### 📅 Organisateur
* **Statistiques et Analytics Avancés** : Vue statistique détaillée par événement, comprenant :
  * Le pourcentage global d'écoulement (taux de remplissage avec un indicateur visuel épuré).
  * Le calcul des revenus nets et bruts générés.
  * La répartition en temps réel des quantités vendues par rapport aux quantités disponibles.
  * Une analyse granulaire par type de ressource (VIP, Classique, Stand, etc.).
* **Contrôle aux Portes (Scanner Manuel)** : Recherche et validation en temps réel des références de billets directement sur le tableau de bord avec retour visuel (Valide / Déjà Utilisé / Invalide).
* **Gestion des Espaces Exposants** : Interface centralisée pour approuver, refuser ou mettre en attente les demandes de stands des exposants tiers.
* **Gestionnaire de Ressources** : Flux de publication pour les concerts et événements avec création dynamique de catégories de billets et de dimensions d'espaces d'exposition.

### 🎪 Exposant (Prestataire)
* **Réservation de Ressources** : Tableau de bord dédié pour réserver des stands, des espaces ou des configurations sur mesure pour les foires et salons.
* **Suivi de Statut** : Mises à jour en direct sur l'état de validation des stands demandés auprès des organisateurs.

### 🛡️ Administrateur (Modérateur)
* **Supervision Globale** : Tableau de bord de haut niveau contenant des statistiques clés, le nombre d'utilisateurs et les publications actives.
* **Système de Certification** : Possibilité de certifier les profils d'artistes ("Certifié") ou de mettre en avant certains événements ("Vedette").
* **Modération de Contenu** : Système de blocage/déblocage pour les événements non conformes.

---

## 🏗️ Architecture & Conception de la Base de Données

ConcertCM+ utilise une architecture hybride élégante pour relier deux domaines distincts :


graph TD
    A[Utilisateur] -->|S'authentifie| B(Authentification & Rôles Spatie)
    B -->|Admin| C[Tableau de bord Admin]
    B -->|Organisateur| D[Analytics Organisateur & Contrôle Portes]
    B -->|Exposant| E[Contrôle Stands Exposant]
    B -->|Spectateur| F[Billets Utilisateur & Suivi Dépenses]
    
    G[Concert - Spécifique] -->|Relation Polymorphique| I[ResourceType (Types de ressources)]
    H[Événement - Générique] -->|Relation Polymorphique| I[ResourceType (Types de ressources)]
    
    I -->|Génère| J[Billets / Réservations de ressources]

### Gestion Polymorphique des Ressources
Pour maintenir une flexibilité maximale, les prix des billets, les catégories et les stands d'exposition sont stockés dans une table unique via une **Relation Polymorphique** (`resourceable`) :
* Les **Concerts** possèdent plusieurs enregistrements `ResourceType` (ex. *Billet VIP*, *Billet Classique*).
* Les **Événements (Foires / Salons)** possèdent plusieurs enregistrements `ResourceType` (ex. *Stand Alimentaire*, *Stand Expo 9m²*).
* Les réservations sont automatiquement dirigées vers `tickets` (pour les entrées de concert avec codes QR) ou `resource_bookings` (pour les stands, espaces et logistique) selon la catégorie de ressource.

---

## 🎨 Design & Système Esthétique

La plateforme est conçue autour du thème sur mesure **"Terrain Vivant"** :
* **Palette HSL Harmonieuse** : Arrière-plans profonds pour les conteneurs (`bg-surface-container-low`, `bg-surface-container-high`), avec des touches de couleurs premium comme le `primary` (bleu/cyan vibrant pour la musique), `secondary` (orange/ambre chaleureux) et `tertiary` (accents vert forêt).
* **Typographie Moderne** : Polices de titres fluides couplées à des polices de corps de texte à haute lisibilité (Outfit / Inter).
* **Animations Subtiles** : Transitions d'échelle douces, sliders héro horizontaux avec navigation par boutons et effets d'incrustation de verre (glassmorphism).
* **Interface Responsive** : Conçue pour s'adapter parfaitement aux écrans mobiles et de bureau.
* **Support du Mode Sombre** : Intégration native du mode sombre/clair via les variables HSL personnalisées.

---

## 🛠️ Pré-requis Système

* **PHP** >= 8.2 (Recommandé : 8.4)
* **Composer** >= 2.0
* **Node.js** >= 18.0 & **NPM** >= 9.0
* **MySQL** >= 8.0 ou **MariaDB** >= 10.4

---

## 🚀 Installation & Configuration

Suivez ces instructions étape par étape pour configurer votre environnement de développement :

### 1. Cloner le Dépôt
```bash
git clone https://github.com/votre-nom-d-utilisateur/polycontent.git
cd polycontent
```

### 2. Installer les Dépendances PHP
```bash
composer install
```

### 3. Installer les Dépendances JavaScript
```bash
npm install
```

### 4. Configurer les Variables d'Environnement
Dupliquez le fichier `.env.example` pour créer votre fichier `.env` local :
```bash
cp .env.example .env
```
Ouvrez `.env` et configurez vos paramètres de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=polycontent
DB_USERNAME=root
DB_PASSWORD=VOTRE_MOT_DE_PASSE
```

### 5. Générer la Clé d'Application
```bash
php artisan key:generate
```

### 6. Lancer les Migrations et Seeders
Cette commande crée toutes les tables de la base de données (y compris les rôles Spatie, les ressources polymorphiques et les tables pivots) et remplit la base avec des utilisateurs de test, des lieux et des données de simulation :
```bash
php artisan migrate:fresh --seed
```

### 7. Créer le Lien de Stockage Public
Pour rendre les affiches de concerts, les bannières d'événements et les avatars de profil accessibles publiquement :
```bash
php artisan storage:link
```

### 8. Lancer les Serveurs de Développement
Démarrez le serveur local Laravel :
```bash
php artisan serve
```
Dans un nouveau terminal, compilez les assets et lancez le serveur Vite :
```bash
npm run dev
```

L'application sera accessible à l'adresse : **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Comptes de Test Pré-configurés

Vous pouvez vous connecter immédiatement en utilisant ces comptes de test. Ils partagent tous le même mot de passe : **`password`**

| Rôle | Adresse Email | Description |
| :--- | :--- | :--- |
| **🛡️ Administrateur** | `admin@concertcm.com` | Accès aux métriques globales, modération des événements, certification des artistes. |
| **📅 Organisateur** | `organisateur@concertcm.com` | Création d'événements, visualisation des fiches analytics détaillées, scan manuel des billets. |
| **🎪 Exposant** | `exposant@concertcm.com` | Réservation de stands/emplacements et suivi de validation des demandes. |
| **🎫 Spectateur** | `yves@example.com` | Réservation de billets, suivi des dépenses, affichage interactif des billets actifs. |

---

## 📁 Fichiers Clés du Projet

* **[`app/Http/Controllers/DashboardController.php`](file:///home/yves/Bureau/polycontent/app/Http/Controllers/DashboardController.php)** : Gère les statistiques globales, les tableaux de bord par rôle et les pages d'analytics individuelles pour chaque événement.
* **[`app/Http/Controllers/TicketController.php`](file:///home/yves/Bureau/polycontent/app/Http/Controllers/TicketController.php)** : Gère la simulation de réservation de billets de concert, la vérification manuelle par code de référence et les contrôles de délais d'annulation.
* **[`app/Services/BookingService.php`](file:///home/yves/Bureau/polycontent/app/Services/BookingService.php)** : Gère les transactions de réservation polymorphiques (choisit dynamiquement de créer un billet d'entrée ou une réservation d'emplacement de stand).
* **[`resources/views/dashboards/organizer_stats.blade.php`](file:///home/yves/Bureau/polycontent/resources/views/dashboards/organizer_stats.blade.php)** : Interface magnifique d'analytics organisateur avec jauges de progression dynamiques.
* **[`resources/views/dashboards/spectator.blade.php`](file:///home/yves/Bureau/polycontent/resources/views/dashboards/spectator.blade.php)** : Espace utilisateur complet et épuré regroupant billets actifs, stands réservés et total des dépenses.

---

## 📄 Licence

La plateforme ConcertCM+ est un logiciel open-source sous licence [MIT](https://opensource.org/licenses/MIT).

## Développé par :
- **Njomgang Njoko Yves**
contact :
-[EMAIL_ADDRESS = yvesnjoko5@gmail.com ]
-[GitHub = [yvesnjoko5@gmail.com](https://github.com/Njokoy) ]
- WhatsApp: +237 6 59354874

## 🎓 Cadre Académique & Environnement de Développement
* **Cadre** : Examen de projet de développement web avancé (Année Académique 2025/2026) — **IAI Cameroun (Yaoundé)**.
* **Outil Principal** : Laravel 10.x / 11.x
* **Outils d'Exécution** : PHP 8.4+, Composer 2.9.3+
## specifications techniques et cahiers de charges 
- /concertcm.md
- /concetcmarchitecture.md
---


C'est une excellente question qui touche au cœur de l'architecture logicielle. Voici une explication claire et professionnelle, adaptée à un document technique ou à la documentation d'un projet :

