
DOCUMENT D'ARCHITECTURE SYSTEME  —  VERSION 1.0

ConcertCM
Architecture Generale · Structure Fichiers · Modele de Base de Donnees
Laravel 11 · PHP 8.2 · MySQL 8 · Redis · Architecture Evolutive


Document	Architecture Systeme Complete — ConcertCM
Version	1.0 — Reference Technique
Date	Avril 2025
Stack	Laravel 11 · PHP 8.2 · MySQL 8.0 · Redis 7 · Nginx · Bootstrap 5
Niveau	Document Technique Interne — Confidentiel
Audience	Equipe Dev · Architecte · Chef de Projet · DBA



Reproduction interdite sans autorisation — Propriete exclusive du projet ConcertCM


1. ARCHITECTURE GENERALE DU SYSTEME


ConcertCM repose sur une architecture trois-tiers classique enrichie de plusieurs couches transversales : cache distribue, file de travaux asynchrones, stockage objet et systeme de notifications multi-canal. Ce choix architectural garantit la stabilite a court terme et l'evolutivite a moyen terme vers une architecture orientee services si la plateforme scale.
1.1 Vue d'Ensemble : Diagramme des Couches

COUCHE	COMPOSANT	TECHNOLOGIE	RESPONSABILITE
Presentation	Templates HTML/CSS/JS	Blade + Bootstrap 5 + Alpine.js	Rendu des vues, interaction UI, formulaires
Routage	Router HTTP	Laravel Router (web.php / api.php)	Distribution des requetes aux Controllers
Logique Metier	Controllers + Services	Laravel MVC + Service Layer	Orchestration, validation, reponse HTTP
Domaine	Models + Policies	Eloquent ORM + Laravel Gates	Entites, regles metier, autorisation
Persistance	Base de données	MySQL 8.0	Stockage relationnel durable
Cache	Cache Layer	Redis 7	Sessions, resultats requetes, rate limiting
Asynchrone	Queue + Workers	Laravel Queue + Redis Driver	Jobs differés : PDF, emails, notifs geo
Taches planifiees	Scheduler	Laravel Task Scheduler + Cron	Rappels concerts, stats quotidiennes
Fichiers	Stockage binaire	Laravel Storage + S3 (OVH/Minio)	Images, PDF billets, affiches
Notification	Multi-canal	Laravel Notifications	Email, base de donnees, push futur
Securite	Authentification	Laravel Breeze + Spatie Permission	Auth, roles, permissions, CSRF


1.2 Flux de Requete HTTP — Cycle de Vie Complet

Lifecycle d'une requete HTTP dans ConcertCM
1. Navigateur -> Nginx (HTTPS/443) -> PHP-FPM -> index.php -> Kernel Laravel -> Stack Middleware (Auth, CSRF, Throttle, Role) -> Router -> Controller -> Service -> Model/Eloquent -> MySQL -> Reponse JSON/Blade -> Cache -> Navigateur

Etape	Composant	Action
1. Requete entrante	Nginx	Termine SSL, proxy vers PHP-FPM socket Unix
2. Bootstrap	public/index.php	Charge autoloader Composer, cree instance Application
3. Kernel HTTP	app/Http/Kernel.php	Applique la pile globale de middlewares
4. Middleware globaux	TrustProxies, CORS, CSRF, Auth	Securite, sessions, authentification
5. Routage	routes/web.php + api.php	Match URI -> Controller@method + middlewares de route
6. Middleware de route	auth, role:admin, throttle:60,1	Verification role, rate limiting par IP
7. Form Request	app/Http/Requests/	Validation des donnees entrantes (regles metier)
8. Controller	app/Http/Controllers/	Orchestre la logique, appelle les Services
9. Service Layer	app/Services/	Logique complexe isolee et testable unitairement
10. Eloquent ORM	app/Models/	Requetes MySQL via QueryBuilder, gestion relations
11. Cache	Redis	Hit cache ? Retourne directement. Miss ? Requete + mise en cache
12. Reponse	Blade view ou JSON	Rendu HTML ou serialisation JSON
13. Apres reponse	Terminate Middleware	Logs, nettoyage, dispatch jobs differés


1.3 Architecture des Processus Serveur

Processus	Commande	Role	Nombre instances
Nginx	systemctl nginx	Serveur web / Reverse proxy	1 (master + workers auto)
PHP-FPM	systemctl php8.2-fpm	Executeur PHP	1 pool (10-50 workers dynamiques)
MySQL	systemctl mysql	Base de donnees principale	1 (+ replica read future)
Redis	systemctl redis	Cache + Queue + Sessions	1
Queue Worker	php artisan queue:work	Traitement jobs asynchrones	2 workers minimum en prod
Scheduler	* * * * * php artisan schedule:run	Taches planifiees (cron)	1 entree crontab
Horizon (optionnel)	php artisan horizon	Dashboard monitoring queues	1 (en production avancee)


1.4 Architecture de Communication entre Composants

Source	Destination	Protocole / Mecanisme	Exemple concret
Controller	Service	Appel methode PHP direct	ConcertController -> ConcertService::create()
Service	Model	Eloquent ORM	TicketService -> Ticket::create([...])
Controller	Job	dispatch() Laravel	TicketController -> dispatch(GenerateTicketPdfJob)
Job	Notification	notify() Eloquent	GenerateTicketPdfJob -> $user->notify(new TicketConfirmation)
Notification	Email	Mailable + SMTP/Mailgun	TicketConfirmation -> toMail() -> Mailgun API
Notification	Database	DatabaseChannel	TicketConfirmation -> toDatabase() -> table notifications
Scheduler	Job	schedule->job()	EventReminderCommand -> dispatch(SendReminderJob)
Model	Observer	Laravel Observer	Concert::saved() -> ConcertObserver -> clear cache
Controller	Cache	Cache facade	Cache::remember('concerts.index', 900, fn)
Laravel	Redis	Predis/PhpRedis	Toutes operations cache, sessions, queues




1.5 Pattern Architectural : MVC + Service Layer + Repository (Optionnel)

ConcertCM adopte le pattern MVC natif Laravel enrichi d'une couche Service obligatoire pour toute logique metier non triviale. Le Repository Pattern est prepare mais optionnel en V1 — il sera active si la plateforme migre vers une architecture multi-sources de donnees.
Pattern	Quand l'utiliser	Exemple ConcertCM
Controller	Reception requete, validation, reponse HTTP uniquement	ConcertController::store() valide et appelle ConcertService
Service	Logique metier complexe, multi-models, orchestration	TicketService::purchase() : verifie stock, cree ticket, paiement, notif
Model + Eloquent	Acces donnees, relations, scopes de requetes	Concert::published()->upcoming()->with('artists')->paginate(15)
Form Request	Validation des donnees entrantes avec regles metier	StoreConcertRequest : regles date future, venue existante, etc.
Resource API	Transformation des modeles en JSON standardise	ConcertResource : formate la reponse pour une future API mobile
Observer	Effets de bord automatiques sur evenements Eloquent	ConcertObserver::updated() -> vide le cache, envoie notifs modif
Policy	Autorisation par ressource et par acteur	ConcertPolicy::update() : seul l'organisateur proprietaire peut editer
Repository (V2+)	Abstraction de la source de donnees	ConcertRepository::findNearby() : MySQL en V1, Elasticsearch en V2




2. STRUCTURE COMPLETE DES FICHIERS DU PROJET


La structure ci-dessous represente l'integralite de l'arborescence du projet ConcertCM sous Laravel 11. Chaque dossier et fichier est justifie par son role dans l'architecture.
2.1 Racine du Projet

concertcm/                          # Racine du projet Laravel
|
+-- app/                            # Code source de l'application
+-- bootstrap/                      # Demarrage framework (cache, providers)
+-- config/                         # Fichiers de configuration
+-- database/                       # Migrations, seeders, factories
+-- public/                         # Point d'entree web (index.php + assets)
+-- resources/                      # Vues Blade, CSS, JS sources
+-- routes/                         # Definition des routes
+-- storage/                        # Logs, cache, fichiers uploadés
+-- tests/                          # Tests unitaires et fonctionnels
+-- vendor/                         # Dependances Composer (jamais edité)
+-- .env                            # Variables d'environnement (hors Git)
+-- .env.example                    # Template .env pour l'equipe
+-- artisan                         # CLI Laravel
+-- composer.json                   # Dependances PHP
+-- package.json                    # Dependances JS (Vite, Alpine.js)
+-- vite.config.js                  # Bundler assets
+-- phpunit.xml                     # Configuration tests
+-- .gitignore                      # Exclusions Git
+-- README.md                       # Documentation projet
+-- deploy.php                      # Script deploiement Deployer.org


2.2 Dossier app/ — Coeur de l'Application

app/
|
+-- Console/
|   +-- Commands/
|   |   +-- SendEventReminders.php        # Rappels J-3 et J-1 concerts
|   |   +-- SendNearbyNotifications.php   # Notifs concerts de proximite
|   |   +-- CleanExpiredTickets.php       # Nettoyage billets expirés
|   |   +-- GenerateDailyStats.php        # Stats quotidiennes dashboard
|   |   +-- ProcessPendingRefunds.php     # Remboursements en attente
|   +-- Kernel.php                        # Enregistrement + schedule des commandes
|
+-- Exceptions/
|   +-- Handler.php                       # Gestionnaire global des exceptions
|   +-- PaymentException.php              # Exception metier paiement
|   +-- TicketSoldOutException.php        # Plus de places disponibles
|   +-- ConcertNotFoundException.php      # Concert introuvable
|
+-- Http/
|   +-- Controllers/
|   |   +-- Auth/
|   |   |   +-- AuthenticatedSessionController.php  # Connexion/Déconnexion
|   |   |   +-- RegisteredUserController.php        # Inscription
|   |   |   +-- PasswordResetController.php         # Reset mot de passe
|   |   |   +-- EmailVerificationController.php     # Verification email
|   |   |
|   |   +-- Admin/
|   |   |   +-- AdminDashboardController.php        # KPIs globaux plateforme
|   |   |   +-- AdminUserController.php             # Gestion utilisateurs
|   |   |   +-- AdminConcertController.php          # Moderation concerts
|   |   |   +-- AdminReviewController.php           # Moderation avis
|   |   |   +-- AdminTransactionController.php      # Suivi transactions
|   |   |   +-- AdminSettingsController.php         # Parametres plateforme
|   |   |
|   |   +-- Organizer/
|   |   |   +-- OrganizerDashboardController.php    # Dashboard organisateur
|   |   |   +-- OrganizerConcertController.php      # CRUD concerts (ses concerts)
|   |   |   +-- OrganizerTicketController.php       # Gestion types billets
|   |   |   +-- OrganizerScanController.php         # Scanner QR code a l'entree
|   |   |   +-- OrganizerStatsController.php        # Statistiques avancees
|   |   |
|   |   +-- Public/
|   |   |   +-- HomeController.php                  # Page d'accueil
|   |   |   +-- ConcertController.php               # Liste + fiche concert
|   |   |   +-- ArtistController.php                # Liste + fiche artiste
|   |   |   +-- VenueController.php                 # Liste + fiche lieu
|   |   |   +-- SearchController.php                # Moteur de recherche global
|   |   |   +-- CalendarController.php              # Calendrier evenements
|   |   |
|   |   +-- User/
|   |   |   +-- ProfileController.php               # Profil utilisateur
|   |   |   +-- TicketController.php                # Mes billets
|   |   |   +-- ReviewController.php                # Mes avis
|   |   |   +-- NotificationController.php          # Mes notifications
|   |   |   +-- FollowController.php                # Suivre/unfollow artiste
|   |   |   +-- PaymentController.php               # Achat billet + webhook
|   |   |
|   |   +-- Api/
|   |   |   +-- V1/
|   |   |       +-- ConcertApiController.php        # API REST concerts (futur mobile)
|   |   |       +-- TicketApiController.php         # API REST billets
|   |   |       +-- ArtistApiController.php         # API REST artistes
|   |   |       +-- AuthApiController.php           # API Auth (tokens Sanctum)
|   |
|   +-- Middleware/
|   |   +-- CheckRole.php                 # Verification role utilisateur
|   |   +-- CheckEmailVerified.php        # Email verifie obligatoire
|   |   +-- LogActivity.php               # Log des actions importantes
|   |   +-- ForceHttps.php                # Redirection HTTPS en production
|   |   +-- SetLocale.php                 # Langue (FR par defaut)
|   |
|   +-- Requests/
|   |   +-- Auth/
|   |   |   +-- LoginRequest.php
|   |   |   +-- RegisterRequest.php
|   |   +-- Concert/
|   |   |   +-- StoreConcertRequest.php   # Validation creation concert
|   |   |   +-- UpdateConcertRequest.php  # Validation modification concert
|   |   +-- Ticket/
|   |   |   +-- PurchaseTicketRequest.php # Validation achat billet
|   |   |   +-- StoreTicketTypeRequest.php
|   |   +-- Review/
|   |   |   +-- StoreReviewRequest.php
|   |   +-- Artist/
|   |       +-- StoreArtistRequest.php
|   |
|   +-- Resources/                        # Transformateurs JSON (API)
|       +-- ConcertResource.php
|       +-- ArtistResource.php
|       +-- TicketResource.php
|       +-- UserResource.php
|       +-- VenueResource.php
|       +-- ConcertCollection.php


+-- Models/
|   +-- User.php                          # Utilisateur + roles + points
|   +-- Concert.php                       # Evenement musical
|   +-- Artist.php                        # Artiste
|   +-- Venue.php                         # Lieu de concert
|   +-- TicketType.php                    # Type de billet (Standard/VIP...)
|   +-- Ticket.php                        # Billet individuel achete
|   +-- Review.php                        # Avis spectateur
|   +-- Transaction.php                   # Transaction de paiement
|   +-- Notification.php                  # Override model notif Laravel
|   +-- Follow.php                        # Pivot user->artist (follow)
|   +-- ConcertArtist.php                 # Pivot concert->artist
|   +-- Badge.php                         # Badges fidelite (V1.1)
|   +-- UserBadge.php                     # Pivot user->badge
|   +-- Referral.php                      # Parrainage (V1.1)
|   +-- PlatformSetting.php               # Parametres config plateforme
|   +-- Scopes/
|       +-- PublishedScope.php            # Scope global : concerts publies
|       +-- UpcomingScope.php             # Scope : concerts futurs
|
+-- Notifications/
|   +-- TicketPurchaseConfirmation.php    # Confirmation achat billet (email+DB)
|   +-- NewConcertFromFollowedArtist.php  # Nouveau concert artiste suivi
|   +-- NearbyEventAlert.php              # Concert a proximite
|   +-- EventReminderD3.php               # Rappel 3 jours avant
|   +-- EventReminderD1.php               # Rappel 1 jour avant
|   +-- ConcertCancellation.php           # Annulation concert
|   +-- ConcertModified.php               # Modification date/lieu concert
|   +-- ReviewPublished.php               # Avis approuve par admin
|   +-- BadgeEarned.php                   # Nouveau badge obtenu (V1.1)
|   +-- ReferralBonus.php                 # Bonus parrainage (V1.1)
|
+-- Jobs/
|   +-- GenerateTicketPdfJob.php          # Generation PDF billet (async)
|   +-- SendNearbyNotificationsJob.php    # Calcul + envoi notifs proximite
|   +-- ProcessPaymentWebhookJob.php      # Traitement retour paiement
|   +-- SendEventReminderJob.php          # Envoi rappel concert programme
|   +-- UpdateConcertStatsJob.php         # Recalcul stats concert
|   +-- BackupDatabaseJob.php             # Declenchement backup
|   +-- ExportTransactionsJob.php         # Export CSV transactions (admin)
|
+-- Services/
|   +-- ConcertService.php               # Logique metier concerts
|   +-- TicketService.php                # Achat, validation, remboursement
|   +-- PaymentService.php               # Abstraction CinetPay / Mobile Money
|   +-- QrCodeService.php                # Generation + validation QR Code
|   +-- PdfService.php                   # Generation PDF billets
|   +-- GeoService.php                   # Calcul distances, geocodage
|   +-- NotificationService.php          # Orchestration notifications
|   +-- SearchService.php                # Logique recherche avancee
|   +-- StatsService.php                 # Calcul statistiques dashboards
|   +-- FidelityService.php              # Points, badges, parrainage (V1.1)
|
+-- Observers/
|   +-- ConcertObserver.php              # Evenements sur Concert (published, cancelled)
|   +-- TicketObserver.php               # Evenements sur Ticket (created, used)
|   +-- UserObserver.php                 # Evenements sur User (registered)
|
+-- Policies/
|   +-- ConcertPolicy.php                # Qui peut creer/editer/supprimer un concert
|   +-- TicketPolicy.php                 # Qui peut voir/telecharger un billet
|   +-- ReviewPolicy.php                 # Qui peut laisser/modifier un avis
|   +-- VenuePolicy.php                  # Gestion des lieux
|   +-- ArtistPolicy.php                 # Gestion des artistes
|
+-- Providers/
|   +-- AppServiceProvider.php           # Bindings, macros, observers
|   +-- AuthServiceProvider.php          # Enregistrement policies + gates
|   +-- EventServiceProvider.php         # Listeners et events Laravel
|   +-- RouteServiceProvider.php         # Configuration routage
|
+-- Events/
|   +-- ConcertPublished.php             # Event : concert publie
|   +-- TicketPurchased.php              # Event : billet achete
|   +-- PaymentConfirmed.php             # Event : paiement confirme
|   +-- ConcertCancelled.php             # Event : concert annule
|
+-- Listeners/
|   +-- OnConcertPublished/
|   |   +-- NotifyFollowers.php          # Notifie les fans de l'artiste
|   |   +-- ClearConcertCache.php        # Vide le cache concerts
|   +-- OnTicketPurchased/
|   |   +-- GenerateTicketPdf.php        # Lance job generation PDF
|   |   +-- DecrementTicketQuota.php     # Reduit stock disponible
|   |   +-- AwardFidelityPoints.php      # Attribue points fidelite (V1.1)
|   +-- OnConcertCancelled/
|       +-- NotifyTicketHolders.php      # Notifie acheteurs
|       +-- ProcessRefunds.php           # Lance les remboursements
|
+-- Rules/
|   +-- FutureDateRule.php               # Validation : date dans le futur
|   +-- ValidCinetPayWebhook.php         # Validation signature webhook
|   +-- TicketAvailable.php              # Validation : quota non epuise
|
+-- Traits/
|   +-- HasSlug.php                      # Generation automatique de slugs
|   +-- HasPoints.php                    # Gestion points fidelite (V1.1)
|   +-- HasGeoLocation.php               # Latitude/longitude helpers
|   +-- Auditable.php                    # Log des modifications


2.3 Dossier config/ — Configuration

config/
+-- app.php                  # Nom app, locale, timezone (Africa/Douala)
+-- auth.php                 # Guards, providers auth
+-- cache.php                # Driver cache (Redis en prod)
+-- database.php             # Connexions MySQL, Redis
+-- filesystems.php          # Disques locaux + S3
+-- mail.php                 # Configuration SMTP/Mailgun
+-- queue.php                # Driver queue (Redis en prod)
+-- session.php              # Driver session (Redis en prod)
+-- logging.php              # Stack logs (daily, stack)
+-- cors.php                 # CORS pour API mobile future
|
+-- cinetpay.php             # Cles API CinetPay + URLs webhook
+-- concert.php              # Config metier (rayon notif, commission %)
+-- geocoding.php            # API geocodage (Nominatim endpoint)
+-- fidelity.php             # Config systeme points/badges (V1.1)


2.4 Dossier database/ — Schema & Donnees

database/
+-- migrations/
|   +-- 2025_01_01_000001_create_users_table.php
|   +-- 2025_01_01_000002_create_password_reset_tokens_table.php
|   +-- 2025_01_01_000003_create_personal_access_tokens_table.php  # Sanctum API
|   +-- 2025_01_02_000001_create_venues_table.php
|   +-- 2025_01_02_000002_create_artists_table.php
|   +-- 2025_01_02_000003_create_concerts_table.php
|   +-- 2025_01_02_000004_create_concert_artist_table.php
|   +-- 2025_01_03_000001_create_ticket_types_table.php
|   +-- 2025_01_03_000002_create_tickets_table.php
|   +-- 2025_01_03_000003_create_transactions_table.php
|   +-- 2025_01_04_000001_create_reviews_table.php
|   +-- 2025_01_04_000002_create_notifications_table.php
|   +-- 2025_01_04_000003_create_user_follows_table.php
|   +-- 2025_01_05_000001_add_spatie_permission_tables.php
|   +-- 2025_01_05_000002_create_platform_settings_table.php
|   +-- 2025_01_05_000003_create_activity_logs_table.php
|   |
|   +-- -- MIGRATIONS V1.1 (Fidelisation) --
|   +-- 2025_06_01_000001_create_badges_table.php
|   +-- 2025_06_01_000002_create_user_badges_table.php
|   +-- 2025_06_01_000003_create_referrals_table.php
|   +-- 2025_06_01_000004_add_points_to_users_table.php
|
+-- factories/
|   +-- UserFactory.php
|   +-- ConcertFactory.php
|   +-- ArtistFactory.php
|   +-- VenueFactory.php
|   +-- TicketTypeFactory.php
|   +-- TicketFactory.php
|   +-- ReviewFactory.php
|
+-- seeders/
    +-- DatabaseSeeder.php             # Orchestrateur principal
    +-- RolesAndPermissionsSeeder.php  # Roles : admin, organizer, spectator
    +-- AdminUserSeeder.php            # Compte admin par defaut
    +-- VenueSeeder.php               # Salles de Yaounde et Douala
    +-- ArtistSeeder.php              # Artistes camerounais
    +-- ConcertSeeder.php             # Concerts de demonstration
    +-- PlatformSettingsSeeder.php    # Parametres par defaut


2.5 Dossier routes/ — Definition des URLs

routes/
+-- web.php          # Routes HTML (Blade) — toutes les pages web
+-- api.php          # Routes API REST (JSON) — futur usage mobile
+-- auth.php         # Routes authentification (Breeze)
+-- console.php      # Routes Artisan (closures commandes)
+-- channels.php     # Canaux broadcast (WebSocket futur)

Extrait de structure des routes web.php
// === ROUTES PUBLIQUES (sans authentification) ===
Route::get('/',          [HomeController::class, 'index'])->name('home');
Route::get('/concerts',  [ConcertController::class, 'index'])->name('concerts.index');
Route::get('/concerts/{slug}', [ConcertController::class, 'show'])->name('concerts.show');
Route::get('/artistes',  [ArtistController::class, 'index'])->name('artists.index');
Route::get('/artistes/{slug}', [ArtistController::class, 'show'])->name('artists.show');
Route::get('/lieux/{slug}',    [VenueController::class, 'show'])->name('venues.show');
Route::get('/calendrier',      [CalendarController::class, 'index'])->name('calendar');
Route::get('/recherche',       [SearchController::class, 'index'])->name('search');

// === WEBHOOK PAIEMENT (sans auth, securise par signature) ===
Route::post('/webhook/cinetpay', [PaymentController::class, 'webhook']);

// === ROUTES AUTHENTIFIEES (spectateur) ===
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mes-billets',   [TicketController::class, 'index']);
    Route::get('/mon-profil',    [ProfileController::class, 'edit']);
    Route::post('/acheter/{ticketType}', [PaymentController::class, 'initiate']);
    Route::post('/follow/{artist}',      [FollowController::class, 'toggle']);
    Route::resource('avis',      ReviewController::class)->only(['store','destroy']);
    Route::get('/notifications', [NotificationController::class, 'index']);
});

// === ROUTES ORGANISATEUR ===
Route::middleware(['auth', 'role:organizer|admin'])->prefix('organisateur')->group(function () {
    Route::get('/dashboard',     [OrganizerDashboardController::class, 'index']);
    Route::resource('concerts',  OrganizerConcertController::class);
    Route::get('/scanner',       [OrganizerScanController::class, 'index']);
    Route::post('/scanner/verify',[OrganizerScanController::class, 'verify']);
});

// === ROUTES ADMIN ===
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard',     [AdminDashboardController::class, 'index']);
    Route::resource('users',     AdminUserController::class);
    Route::resource('concerts',  AdminConcertController::class);
    Route::get('/transactions',  [AdminTransactionController::class, 'index']);
    Route::post('/avis/{review}/approuver', [AdminReviewController::class, 'approve']);
});


2.6 Dossier resources/ — Vues et Assets

resources/
+-- views/
|   +-- layouts/
|   |   +-- app.blade.php           # Layout principal (nav + footer)
|   |   +-- admin.blade.php         # Layout espace admin
|   |   +-- organizer.blade.php     # Layout espace organisateur
|   |   +-- auth.blade.php          # Layout pages auth
|   |
|   +-- components/                 # Composants Blade reutilisables
|   |   +-- concert-card.blade.php  # Carte concert (liste/grid)
|   |   +-- artist-card.blade.php
|   |   +-- ticket-badge.blade.php  # Badge statut billet
|   |   +-- rating-stars.blade.php  # Etoiles notation
|   |   +-- notification-bell.blade.php
|   |   +-- map-embed.blade.php     # Carte OpenStreetMap
|   |   +-- payment-modal.blade.php # Modal paiement Mobile Money
|   |   +-- qr-scanner.blade.php   # Interface scan QR (Alpine.js)
|   |
|   +-- public/                     # Pages publiques
|   |   +-- home/index.blade.php
|   |   +-- concerts/
|   |   |   +-- index.blade.php     # Liste concerts avec filtres
|   |   |   +-- show.blade.php      # Fiche concert complete
|   |   +-- artists/
|   |   |   +-- index.blade.php
|   |   |   +-- show.blade.php
|   |   +-- venues/show.blade.php
|   |   +-- calendar/index.blade.php
|   |   +-- search/index.blade.php
|   |
|   +-- user/                       # Espace spectateur
|   |   +-- tickets/index.blade.php
|   |   +-- tickets/show.blade.php  # Billet avec QR Code
|   |   +-- profile/edit.blade.php
|   |   +-- notifications/index.blade.php
|   |
|   +-- organizer/                  # Espace organisateur
|   |   +-- dashboard/index.blade.php
|   |   +-- concerts/
|   |   |   +-- index.blade.php
|   |   |   +-- create.blade.php
|   |   |   +-- edit.blade.php
|   |   |   +-- show.blade.php      # Stats concert
|   |   +-- scanner/index.blade.php # Interface scanner QR
|   |
|   +-- admin/                      # Espace admin
|   |   +-- dashboard/index.blade.php
|   |   +-- users/index.blade.php
|   |   +-- concerts/index.blade.php
|   |   +-- reviews/index.blade.php
|   |   +-- transactions/index.blade.php
|   |
|   +-- emails/                     # Templates emails
|   |   +-- ticket-confirmation.blade.php
|   |   +-- event-reminder.blade.php
|   |   +-- concert-cancelled.blade.php
|   |   +-- welcome.blade.php
|   |   +-- nearby-event.blade.php
|   |
|   +-- pdf/
|       +-- ticket.blade.php        # Template PDF billet (DomPDF)
|
+-- css/
|   +-- app.css                     # Styles globaux + Bootstrap custom
|   +-- dashboard.css               # Styles dashboards
|
+-- js/
    +-- app.js                      # Point d'entree JS (Alpine.js, etc.)
    +-- modules/
        +-- qr-scanner.js           # Scanner QR (jsQR library)
        +-- map.js                  # Leaflet + OpenStreetMap
        +-- payment.js              # Gestion modal paiement
        +-- concert-filters.js      # Filtres dynamiques liste concerts
        +-- notifications.js        # Polling notifs in-app




3. MODELE COMPLET DE BASE DE DONNEES


Le schema de base de donnees de ConcertCM est concu selon trois principes : integrite relationnelle stricte (cles etrangeres avec contraintes), extensibilite (tables de jonction generiques, champs JSON pour attributs variables) et performance (index strategiques sur les colonnes de recherche et de filtrage).
Conventions de nommage
Tables : snake_case pluriel (concerts, ticket_types). Colonnes PK : id (BIGINT UNSIGNED AUTO_INCREMENT). FK : {table_singulier}_id. Timestamps : created_at, updated_at sur toutes les tables. Soft deletes : deleted_at sur les tables critiques (concerts, users, tickets).


3.1 Diagramme des Relations (ERD Textuel)

users           ||--o{ tickets          : 'achete'
users           ||--o{ reviews          : 'redige'
users           ||--o{ transactions     : 'initie'
users           }o--o{ artists          : 'suit (user_follows)'
users           ||--o{ notifications    : 'recoit'
users           ||--o{ user_badges      : 'obtient (V1.1)'
users           ||--o{ referrals        : 'parraine (V1.1)'

concerts        }o--|| venues           : 'se deroule dans'
concerts        }o--|| users            : 'organise par'
concerts        }o--o{ artists          : 'met en scene (concert_artist)'
concerts        ||--o{ ticket_types     : 'propose'
concerts        ||--o{ reviews          : 'recoit'

ticket_types    ||--o{ tickets          : 'genere'
tickets         ||--o{ transactions     : 'associee a'

venues          ||--o{ concerts         : 'accueille'
artists         }o--o{ concerts         : 'joue dans (concert_artist)'
artists         ||--o{ user_follows     : 'est suivi par'


3.2 Table : users

CREATE TABLE users (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(120)    NOT NULL,
    email               VARCHAR(180)    NOT NULL UNIQUE,
    password            VARCHAR(255)    NOT NULL,
    role                ENUM('admin','organizer','spectator') NOT NULL DEFAULT 'spectator',
    avatar              VARCHAR(255)    NULL,
    phone               VARCHAR(20)     NULL,
    city                VARCHAR(80)     NULL,
    latitude            DECIMAL(10,7)   NULL,         -- GPS pour notifs proximite
    longitude           DECIMAL(10,7)   NULL,
    notification_radius INT             DEFAULT 30,   -- km rayon notifications
    points              INT UNSIGNED    DEFAULT 0,    -- ConcertCoins fidelite
    referral_code       VARCHAR(20)     NOT NULL UNIQUE,  -- Code parrainage unique
    referred_by         BIGINT UNSIGNED NULL,
    preferences         JSON            NULL,         -- genres musicaux preferes
    is_active           BOOLEAN         DEFAULT TRUE,
    email_verified_at   TIMESTAMP       NULL,
    remember_token      VARCHAR(100)    NULL,
    last_login_at       TIMESTAMP       NULL,
    deleted_at          TIMESTAMP       NULL,         -- Soft delete
    created_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_city (city),
    INDEX idx_geo  (latitude, longitude),
    INDEX idx_ref  (referral_code),
    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE SET NULL
);


3.3 Table : venues

CREATE TABLE venues (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150)   NOT NULL,
    slug         VARCHAR(160)   NOT NULL UNIQUE,
    address      VARCHAR(255)   NOT NULL,
    city         VARCHAR(80)    NOT NULL,
    district     VARCHAR(80)    NULL,                 -- Quartier (Bastos, Akwa...)
    country      VARCHAR(60)    NOT NULL DEFAULT 'Cameroun',
    capacity     INT UNSIGNED   NOT NULL,
    latitude     DECIMAL(10,7)  NOT NULL,
    longitude    DECIMAL(10,7)  NOT NULL,
    description  TEXT           NULL,
    amenities    JSON           NULL,    -- {'parking':true,'bar':true,'clim':false}
    photos       JSON           NULL,    -- ['path/photo1.jpg', 'path/photo2.jpg']
    website      VARCHAR(255)   NULL,
    phone        VARCHAR(20)    NULL,
    is_active    BOOLEAN        DEFAULT TRUE,
    created_by   BIGINT UNSIGNED NOT NULL,
    created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_city (city),
    INDEX idx_geo  (latitude, longitude),
    FULLTEXT idx_ft_venue (name, address, city),
    FOREIGN KEY (created_by) REFERENCES users(id)
);


3.4 Table : artists

CREATE TABLE artists (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(120)   NOT NULL,
    slug         VARCHAR(130)   NOT NULL UNIQUE,
    bio          TEXT           NULL,
    photo        VARCHAR(255)   NULL,
    genre        VARCHAR(80)    NULL,                 -- Genre musical principal
    genres       JSON           NULL,                 -- Plusieurs genres possible
    city         VARCHAR(80)    NULL,
    country      VARCHAR(60)    DEFAULT 'Cameroun',
    instagram    VARCHAR(255)   NULL,
    facebook     VARCHAR(255)   NULL,
    youtube      VARCHAR(255)   NULL,
    spotify      VARCHAR(255)   NULL,
    soundcloud   VARCHAR(255)   NULL,
    website      VARCHAR(255)   NULL,
    followers_count INT UNSIGNED DEFAULT 0,           -- Cache compteur (denorm)
    concerts_count  INT UNSIGNED DEFAULT 0,           -- Cache compteur (denorm)
    avg_rating      DECIMAL(3,2) DEFAULT 0.00,        -- Cache note moyenne
    is_verified  BOOLEAN        DEFAULT FALSE,        -- Artiste certifie plateforme
    is_active    BOOLEAN        DEFAULT TRUE,
    created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_genre (genre),
    INDEX idx_city  (city),
    FULLTEXT idx_ft_artist (name, bio, genre)
);


3.5 Table : concerts

CREATE TABLE concerts (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(200)    NOT NULL,
    slug            VARCHAR(220)    NOT NULL UNIQUE,
    description     TEXT            NULL,
    poster          VARCHAR(255)    NULL,             -- Chemin affiche principale
    gallery         JSON            NULL,             -- Photos additionnelles
    date            DATE            NOT NULL,
    start_time      TIME            NOT NULL,
    end_time        TIME            NULL,
    timezone        VARCHAR(50)     DEFAULT 'Africa/Douala',
    venue_id        BIGINT UNSIGNED NOT NULL,
    organizer_id    BIGINT UNSIGNED NOT NULL,
    category        VARCHAR(80)     NULL,             -- Genre musical principal
    tags            JSON            NULL,             -- Tags libres
    status          ENUM('draft','published','cancelled','completed')
                                    NOT NULL DEFAULT 'draft',
    capacity        INT UNSIGNED    NULL,             -- NULL = pas de limite
    min_age         TINYINT UNSIGNED DEFAULT 0,       -- Age minimum entree
    is_free         BOOLEAN         DEFAULT FALSE,
    featured        BOOLEAN         DEFAULT FALSE,    -- Mis en avant page accueil
    featured_until  TIMESTAMP       NULL,             -- Expiration mise en avant
    views_count     INT UNSIGNED    DEFAULT 0,        -- Compteur vues (denorm)
    tickets_sold    INT UNSIGNED    DEFAULT 0,        -- Compteur ventes (denorm)
    revenue_total   DECIMAL(12,2)   DEFAULT 0.00,     -- CA total (denorm)
    avg_rating      DECIMAL(3,2)    DEFAULT 0.00,     -- Note moyenne (denorm)
    cancelled_at    TIMESTAMP       NULL,
    cancelled_reason TEXT           NULL,
    published_at    TIMESTAMP       NULL,
    deleted_at      TIMESTAMP       NULL,             -- Soft delete
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status       (status),
    INDEX idx_date         (date),
    INDEX idx_organizer    (organizer_id),
    INDEX idx_venue        (venue_id),
    INDEX idx_category     (category),
    INDEX idx_featured     (featured, featured_until),
    INDEX idx_status_date  (status, date),
    FULLTEXT idx_ft_concert (title, description, category),
    FOREIGN KEY (venue_id)     REFERENCES venues(id),
    FOREIGN KEY (organizer_id) REFERENCES users(id)
);


3.6 Table Pivot : concert_artist

CREATE TABLE concert_artist (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    concert_id   BIGINT UNSIGNED NOT NULL,
    artist_id    BIGINT UNSIGNED NOT NULL,
    is_headliner BOOLEAN         DEFAULT FALSE,   -- Artiste tete d'affiche
    order        TINYINT UNSIGNED DEFAULT 0,      -- Ordre de passage
    fee          DECIMAL(10,2)   NULL,            -- Cachet (confidentiel)
    notes        TEXT            NULL,
    created_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_concert_artist (concert_id, artist_id),
    FOREIGN KEY (concert_id) REFERENCES concerts(id) ON DELETE CASCADE,
    FOREIGN KEY (artist_id)  REFERENCES artists(id)  ON DELETE CASCADE
);


3.7 Table : ticket_types

CREATE TABLE ticket_types (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    concert_id      BIGINT UNSIGNED NOT NULL,
    name            VARCHAR(80)     NOT NULL,   -- 'Standard', 'VIP', 'VVIP'...
    description     TEXT            NULL,        -- Avantages inclus
    price           DECIMAL(10,2)   NOT NULL,    -- Prix en FCFA
    quota           INT UNSIGNED    NOT NULL,    -- Nombre de places total
    sold_count      INT UNSIGNED    DEFAULT 0,   -- Vendus (denorm, pour perf)
    reserved_count  INT UNSIGNED    DEFAULT 0,   -- En cours de paiement (<15min)
    sale_starts_at  TIMESTAMP       NULL,        -- Ouverture vente
    sale_ends_at    TIMESTAMP       NULL,        -- Fermeture vente
    max_per_user    TINYINT UNSIGNED DEFAULT 5,  -- Max billets par utilisateur
    is_active       BOOLEAN         DEFAULT TRUE,
    sort_order      TINYINT UNSIGNED DEFAULT 0,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_concert (concert_id),
    FOREIGN KEY (concert_id) REFERENCES concerts(id) ON DELETE CASCADE
);


3.8 Table : tickets

CREATE TABLE tickets (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid             CHAR(36)        NOT NULL UNIQUE,  -- UUID v4 pour QR Code
    concert_id       BIGINT UNSIGNED NOT NULL,
    ticket_type_id   BIGINT UNSIGNED NOT NULL,
    user_id          BIGINT UNSIGNED NOT NULL,
    transaction_id   BIGINT UNSIGNED NULL,
    reference        VARCHAR(40)     NOT NULL UNIQUE,  -- REF-2025-XXXXXX
    price_paid       DECIMAL(10,2)   NOT NULL,
    status           ENUM('pending','confirmed','used','cancelled','refunded')
                                     NOT NULL DEFAULT 'pending',
    qr_code_path     VARCHAR(255)    NULL,             -- Chemin image QR generee
    pdf_path         VARCHAR(255)    NULL,             -- Chemin PDF billet
    used_at          TIMESTAMP       NULL,             -- Date scan a l'entree
    scanned_by       BIGINT UNSIGNED NULL,             -- Organisateur qui a scanne
    cancelled_at     TIMESTAMP       NULL,
    refunded_at      TIMESTAMP       NULL,
    refund_amount    DECIMAL(10,2)   NULL,
    metadata         JSON            NULL,             -- Donnees additionnelles
    created_at       TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user       (user_id),
    INDEX idx_concert    (concert_id),
    INDEX idx_status     (status),
    INDEX idx_uuid       (uuid),
    FOREIGN KEY (concert_id)     REFERENCES concerts(id),
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_types(id),
    FOREIGN KEY (user_id)        REFERENCES users(id),
    FOREIGN KEY (scanned_by)     REFERENCES users(id) ON DELETE SET NULL
);


3.9 Table : transactions

CREATE TABLE transactions (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id          BIGINT UNSIGNED NOT NULL,
    ticket_id        BIGINT UNSIGNED NULL,
    provider         VARCHAR(40)     NOT NULL,         -- 'cinetpay','mtn','orange'
    provider_ref     VARCHAR(100)    NULL UNIQUE,      -- Reference retournee par le provider
    internal_ref     VARCHAR(40)     NOT NULL UNIQUE,  -- Notre reference interne
    amount           DECIMAL(12,2)   NOT NULL,
    currency         VARCHAR(5)      DEFAULT 'XAF',    -- Franc CFA
    commission       DECIMAL(10,2)   DEFAULT 0.00,     -- Commission ConcertCM
    payment_method   VARCHAR(40)     NULL,             -- 'mtn_momo','orange_money','card'
    phone_number     VARCHAR(20)     NULL,             -- Numero Mobile Money
    status           ENUM('pending','processing','completed','failed','refunded')
                                     NOT NULL DEFAULT 'pending',
    initiated_at     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    confirmed_at     TIMESTAMP       NULL,
    failed_at        TIMESTAMP       NULL,
    failure_reason   TEXT            NULL,
    webhook_payload  JSON            NULL,             -- Payload brut webhook (debug)
    ip_address       VARCHAR(45)     NULL,
    created_at       TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user     (user_id),
    INDEX idx_status   (status),
    INDEX idx_provider (provider, provider_ref),
    INDEX idx_date     (created_at),
    FOREIGN KEY (user_id)   REFERENCES users(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE SET NULL
);


3.10 Table : reviews

CREATE TABLE reviews (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    concert_id   BIGINT UNSIGNED NOT NULL,
    user_id      BIGINT UNSIGNED NOT NULL,
    rating       TINYINT UNSIGNED NOT NULL,            -- 1 a 5 etoiles
    title        VARCHAR(120)    NULL,
    comment      TEXT            NULL,
    status       ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    moderated_by BIGINT UNSIGNED NULL,
    moderated_at TIMESTAMP       NULL,
    rejection_reason TEXT        NULL,
    helpful_count   INT UNSIGNED DEFAULT 0,           -- Votes 'utile'
    created_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_user_concert (user_id, concert_id),  -- 1 avis par concert/user
    INDEX idx_concert (concert_id),
    INDEX idx_status  (status),
    INDEX idx_rating  (rating),
    FOREIGN KEY (concert_id)   REFERENCES concerts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)      REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (moderated_by) REFERENCES users(id)    ON DELETE SET NULL
);


3.11 Table : user_follows (Follow Artiste)

CREATE TABLE user_follows (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      BIGINT UNSIGNED NOT NULL,
    artist_id    BIGINT UNSIGNED NOT NULL,
    created_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_follow (user_id, artist_id),
    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE CASCADE
);


3.12 Table : notifications (Laravel Native)

CREATE TABLE notifications (
    id              CHAR(36)        NOT NULL PRIMARY KEY,  -- UUID
    type            VARCHAR(255)    NOT NULL,              -- Classe Notification
    notifiable_type VARCHAR(255)    NOT NULL,              -- 'App\Models\User'
    notifiable_id   BIGINT UNSIGNED NOT NULL,
    data            TEXT            NOT NULL,              -- JSON payload
    read_at         TIMESTAMP       NULL,
    created_at      TIMESTAMP       NULL,
    updated_at      TIMESTAMP       NULL,

    INDEX idx_notifiable (notifiable_type, notifiable_id),
    INDEX idx_read_at    (read_at)
);


3.13 Table : activity_logs (Audit Trail)

CREATE TABLE activity_logs (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        BIGINT UNSIGNED NULL,
    action         VARCHAR(80)     NOT NULL,   -- 'concert.published', 'ticket.purchased'
    subject_type   VARCHAR(80)     NULL,       -- 'Concert', 'Ticket'...
    subject_id     BIGINT UNSIGNED NULL,
    properties     JSON            NULL,       -- Donnees contextuelles
    ip_address     VARCHAR(45)     NULL,
    user_agent     VARCHAR(255)    NULL,
    created_at     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_user    (user_id),
    INDEX idx_action  (action),
    INDEX idx_subject (subject_type, subject_id),
    INDEX idx_date    (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);


3.14 Table : platform_settings

CREATE TABLE platform_settings (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key           VARCHAR(80)     NOT NULL UNIQUE,  -- 'commission_rate', 'notification_radius'
    value         TEXT            NOT NULL,
    type          ENUM('string','integer','float','boolean','json') DEFAULT 'string',
    description   VARCHAR(255)    NULL,
    is_public     BOOLEAN         DEFAULT FALSE,    -- Accessible via API publique
    created_at    TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);




3.15 Tables V1.1 — Systeme de Fidelisation

Ces tables sont preparees et migrables independamment en version 1.1 sans toucher aux tables existantes. Elles sont architecturees pour ne pas impacter les performances de la V1.0.
-- BADGES --
CREATE TABLE badges (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code        VARCHAR(40)     NOT NULL UNIQUE,  -- 'fan_decouverte', 'fan_vip'
    name        VARCHAR(80)     NOT NULL,
    description TEXT            NULL,
    icon        VARCHAR(255)    NULL,
    condition   JSON            NOT NULL,         -- {tickets_count: 10, ...}
    points_reward INT UNSIGNED  DEFAULT 0,
    is_active   BOOLEAN         DEFAULT TRUE,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
);

-- USER-BADGES (pivot) --
CREATE TABLE user_badges (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NOT NULL,
    badge_id    BIGINT UNSIGNED NOT NULL,
    earned_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_badge (user_id, badge_id),
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
);

-- REFERRALS --
CREATE TABLE referrals (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_id    BIGINT UNSIGNED NOT NULL,      -- Parrain
    referred_id    BIGINT UNSIGNED NOT NULL,      -- Filleul
    status         ENUM('pending','activated','rewarded') DEFAULT 'pending',
    reward_points  INT UNSIGNED    DEFAULT 100,
    rewarded_at    TIMESTAMP       NULL,
    created_at     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_referral (referrer_id, referred_id),
    FOREIGN KEY (referrer_id) REFERENCES users(id),
    FOREIGN KEY (referred_id) REFERENCES users(id)
);

-- POINTS TRANSACTIONS --
CREATE TABLE points_transactions (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      BIGINT UNSIGNED NOT NULL,
    amount       INT             NOT NULL,         -- Positif = gain, negatif = depense
    type         VARCHAR(40)     NOT NULL,         -- 'ticket_purchase','review','referral'
    subject_type VARCHAR(80)     NULL,
    subject_id   BIGINT UNSIGNED NULL,
    balance_after INT UNSIGNED   NOT NULL,         -- Solde apres operation
    description  VARCHAR(255)    NULL,
    created_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);




3.16 Index de Performance — Strategies

Table	Index	Type	Justification
concerts	(status, date)	Composite	Requete principale : concerts publies a venir
concerts	FULLTEXT(title, description, category)	FULLTEXT	Moteur de recherche texte libre
concerts	(featured, featured_until)	Composite	Page d'accueil : concerts en avant
venues	(latitude, longitude)	Composite	Recherche geographique de proximite
users	(latitude, longitude)	Composite	Calcul distance user<->concert
tickets	(uuid)	UNIQUE	Validation QR Code : lookup O(1)
tickets	(status, concert_id)	Composite	Comptage billets vendus par concert
transactions	(provider, provider_ref)	Composite	Traitement webhooks paiement
notifications	(notifiable_id, read_at)	Composite	Chargement notifs non lues
activity_logs	(created_at)	Simple	Purge automatique logs anciens (>90j)


3.17 Strategie d'Evolution de la Base de Donnees

Principe d'evolution sans rupture (Zero Downtime Migrations)
Toutes les nouvelles colonnes sont ajoutees avec une valeur DEFAULT ou NULL pour ne pas bloquer la production. Les nouvelles tables sont independantes. Les changements de type ENUM sont geres via des migrations en deux etapes (ajout nouvelle colonne, migration des donnees, suppression ancienne colonne).

Version	Evolution Base de Donnees	Migration Strategy
V1.0	Schema core : 13 tables principales	Migration complete initiale
V1.1	+ 4 tables fidelisation (badges, referrals, points)	ADD TABLE (non destructif)
V1.2	+ Table media (galerie enrichie artistes/concerts)	ADD TABLE + colonne JSON existante migree
V2.0	+ Multi-tenancy : column organizer_plan_id	ADD COLUMN NOT NULL DEFAULT (migration en 2 etapes)
V2.1	+ Elasticsearch sync : table search_index	ADD TABLE + Laravel Scout driver change
V3.0	Partitionnement table tickets par annee	Migration avec pt-online-schema-change (sans lock)
V3.1	Replica MySQL read-only pour dashboards stats	Ajout connexion read dans database.php




4. RELATIONS ELOQUENT & MODELES


Cette section documente les relations Eloquent implementees dans chaque Model, les scopes globaux et locaux, ainsi que les attributs calcules (accessors/mutators) cles.
4.1 Model Concert — Relations & Scopes

class Concert extends Model {
    use HasFactory, SoftDeletes, HasSlug;

    // RELATIONS
    public function venue()        { return $this->belongsTo(Venue::class); }
    public function organizer()    { return $this->belongsTo(User::class, 'organizer_id'); }
    public function artists()      { return $this->belongsToMany(Artist::class)
                                         ->withPivot('is_headliner','order'); }
    public function ticketTypes()  { return $this->hasMany(TicketType::class); }
    public function tickets()      { return $this->hasMany(Ticket::class); }
    public function reviews()      { return $this->hasMany(Review::class)
                                         ->where('status','approved'); }

    // SCOPES
    public function scopePublished($q)  { return $q->where('status','published'); }
    public function scopeUpcoming($q)   { return $q->where('date','>=',today()); }
    public function scopePast($q)       { return $q->where('date','<', today()); }
    public function scopeFeatured($q)   { return $q->where('featured',true)
                                              ->where('featured_until','>',now()); }
    public function scopeInCity($q,$city){ return $q->whereHas('venue',
                                              fn($v)=>$v->where('city',$city)); }
    public function scopeByGenre($q,$g) { return $q->where('category',$g); }

    // ACCESSORS
    public function getIsSoldOutAttribute() {
        return $this->ticketTypes->every(fn($t) => $t->available_count === 0);
    }
    public function getAvailableTicketsCountAttribute() {
        return $this->ticketTypes->sum('available_count');
    }
}


4.2 Model User — Relations & Helpers

class User extends Authenticatable {
    use HasRoles, HasFactory, SoftDeletes, HasPoints, HasGeoLocation;
    //   ^Spatie   ^Laravel              ^Custom traits

    public function tickets()     { return $this->hasMany(Ticket::class); }
    public function reviews()     { return $this->hasMany(Review::class); }
    public function transactions(){ return $this->hasMany(Transaction::class); }
    public function follows()     { return $this->belongsToMany(Artist::class,
                                         'user_follows'); }
    public function badges()      { return $this->belongsToMany(Badge::class,
                                         'user_badges')->withPivot('earned_at'); }

    // HELPER : concerts a venir de cet utilisateur
    public function upcomingConcerts() {
        return Concert::published()->upcoming()
            ->whereHas('tickets', fn($q) =>
                $q->where('user_id', $this->id)->where('status','confirmed')
            );
    }

    // HELPER : distance depuis un concert
    public function distanceTo(Concert $c): float {
        return GeoService::haversine(
            $this->latitude, $this->longitude,
            $c->venue->latitude, $c->venue->longitude
        );
    }
}


4.3 Model TicketType — Gestion du Stock

class TicketType extends Model {
    public function concert() { return $this->belongsTo(Concert::class); }
    public function tickets() { return $this->hasMany(Ticket::class); }

    // Calcul places disponibles en temps reel
    public function getAvailableCountAttribute(): int {
        return max(0, $this->quota - $this->sold_count - $this->reserved_count);
    }

    // Verification disponibilite avec verrou DB (anti-oversell)
    public function reserveTickets(int $qty): bool {
        return DB::transaction(function() use ($qty) {
            $type = TicketType::lockForUpdate()->find($this->id);
            if ($type->available_count < $qty) return false;
            $type->increment('reserved_count', $qty);
            return true;
        });
    }
}




5. COUCHE SERVICE — LOGIQUE METIER


La couche Service isole toute logique metier complexe des Controllers, la rendant testable unitairement et reutilisable. Chaque Service est injecte via le conteneur IoC de Laravel.
5.1 TicketService — Orchestrateur d'Achat

class TicketService {
    public function __construct(
        private PaymentService $payment,
        private QrCodeService  $qrCode,
        private PdfService     $pdf,
    ) {}

    public function initiatePurchase(User $user, TicketType $type, array $data): Transaction {
        // 1. Verifier disponibilite avec verrou
        if (!$type->reserveTickets(1))
            throw new TicketSoldOutException();

        // 2. Creer transaction 'pending'
        $transaction = Transaction::create([
            'user_id'        => $user->id,
            'amount'         => $type->price,
            'internal_ref'   => 'REF-'.strtoupper(Str::random(10)),
            'payment_method' => $data['method'],  // mtn_momo | orange_money
            'phone_number'   => $data['phone'],
            'status'         => 'pending',
        ]);

        // 3. Initier paiement chez CinetPay
        $paymentUrl = $this->payment->initiate($transaction);

        return $transaction;
    }

    public function confirmPurchase(Transaction $tx): Ticket {
        return DB::transaction(function() use ($tx) {
            // 1. Creer le billet confirme
            $ticket = Ticket::create([
                'uuid'           => Str::uuid(),
                'concert_id'     => $tx->ticket_type->concert_id,
                'ticket_type_id' => $tx->ticket_type_id,
                'user_id'        => $tx->user_id,
                'transaction_id' => $tx->id,
                'reference'      => $this->generateRef(),
                'price_paid'     => $tx->amount,
                'status'         => 'confirmed',
            ]);

            // 2. Incrementer sold_count, decrementer reserved_count
            $tx->ticketType->increment('sold_count');
            $tx->ticketType->decrement('reserved_count');

            // 3. Dispatcher l'evenement (listeners asynchrones)
            event(new TicketPurchased($ticket));

            return $ticket;
        });
    }
}


5.2 GeoService — Calcul de Proximite

class GeoService {
    // Formule Haversine : distance entre 2 points GPS en km
    public static function haversine(float $lat1, float $lon1,
                                     float $lat2, float $lon2): float {
        $R = 6371; // Rayon Terre en km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2)*sin($dLat/2) +
             cos(deg2rad($lat1))*cos(deg2rad($lat2)) *
             sin($dLon/2)*sin($dLon/2);
        return $R * 2 * atan2(sqrt($a), sqrt(1-$a));
    }

    // Trouver utilisateurs dans un rayon autour d'un concert
    public function findUsersNearConcert(Concert $concert, int $radiusKm = 30): Collection {
        $lat = $concert->venue->latitude;
        $lon = $concert->venue->longitude;
        // Requete MySQL avec calcul distance (optimisee par index lat/lng)
        return User::selectRaw('*, (
            6371 * acos(cos(radians(?)) * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?)) * sin(radians(latitude)))
        ) AS distance', [$lat, $lon, $lat])
        ->whereNotNull('latitude')
        ->having('distance', '<=', $radiusKm)
        ->orderBy('distance')
        ->get();
    }
}




6. SECURITE & BONNES PRATIQUES


6.1 Matrice de Securite Complete

Vecteur d'Attaque	Niveau	Contre-mesure	Implementation
Injection SQL	Critique	ORM uniquement — zero SQL brut	Eloquent QueryBuilder partout, bindngs PDO
CSRF	Critique	Token CSRF obligatoire	Middleware VerifyCsrfToken + @csrf Blade
XSS	Critique	Echappement auto des variables	{{ $var }} Blade echappe, {!! !!} interdit sauf admin
Acces non autorise	Critique	Auth + Roles + Policies	Middleware auth + Spatie roles + Policy par ressource
Brute force login	Haute	Rate limiting adaptatif	throttle:5,1 sur routes auth (5 tentatives/minute)
Faux webhooks paiement	Critique	Signature HMAC-SHA256	ValidCinetPayWebhook Rule + signature verification
Oversell billets	Critique	Verrou DB transactionnel	SELECT FOR UPDATE dans DB::transaction()
Upload malveillant	Haute	Validation stricte MIME	mimes:jpg,jpeg,png,webp + max:2048 + antivirus futur
Exposition variables	Critique	Fichier .env hors Git	.gitignore strict + variables serveur en prod
Enumeration users	Moyenne	Messages erreurs generiques	Meme message pour email inconnu et mdp incorrect
Session hijacking	Haute	Regeneration session post-login	$request->session()->regenerate() apres auth
Fuite logs	Haute	Logs hors dossier public	storage/logs/ inaccessible depuis web (Nginx deny)
DDoS basic	Moyenne	Rate limiting global Nginx	limit_req_zone en nginx.conf


6.2 Configuration Nginx de Production

server {
    listen 443 ssl http2;
    server_name concertcm.cm;
    root /var/www/concertcm/public;

    ssl_certificate     /etc/letsencrypt/live/concertcm.cm/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/concertcm.cm/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    # Headers securite
    add_header X-Frame-Options 'SAMEORIGIN';
    add_header X-Content-Type-Options 'nosniff';
    add_header Strict-Transport-Security 'max-age=31536000';
    add_header Referrer-Policy 'strict-origin-when-cross-origin';

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    location /login { limit_req zone=login burst=3 nodelay; }

    # Protection dossiers sensibles
    location ~ /\.env { deny all; }
    location ~ /storage/logs { deny all; }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
# Redirection HTTP -> HTTPS
server { listen 80; return 301 https://$host$request_uri; }




7. EVOLUTIVITE & ROADMAP ARCHITECTURE


ConcertCM est architecturee pour grandir progressivement sans refonte majeure. Chaque evolution est planifiee pour etre deployable independamment en production.
7.1 Roadmap d'Evolution Architecture

Version	Horizon	Evolution Technique	Impact
V1.0	J+0	Laravel MVC + MySQL + Redis + CinetPay	Base de production stable
V1.1	J+90	Systeme fidelisation (points, badges, parrainage)	4 nouvelles tables, 0 breaking change
V1.2	J+150	API REST complete (Laravel Sanctum) pour mobile	Nouveaux routes api.php, Resources existantes
V1.3	J+180	Application mobile PWA (Progressive Web App)	Manifest.json + Service Worker + Push API
V2.0	J+270	Multi-pays (Gabon, Congo, Cote d'Ivoire)	Column country + multi-currency (FCFA/CFA)
V2.1	J+330	Recherche avancee Meilisearch (remplace FULLTEXT)	Laravel Scout + Meilisearch driver
V2.2	J+360	Application mobile native (React Native)	API V2 + auth Sanctum tokens
V3.0	J+540	Architecture micro-services (Billing isole)	Service Billing independant + event bus
V3.1	J+600	Live streaming concerts	WebRTC ou HLS (Wowza/Cloudflare Stream)


7.2 Principes Garantissant l'Evolutivite

Principe	Application Concrete dans ConcertCM
Single Responsibility	Un Controller = un domaine. Un Service = une logique metier. Un Job = une tache async.
Dependency Injection	Tous les Services sont injectes via le container Laravel, jamais instancies manuellement.
Event-Driven Architecture	Les actions metier emettent des Events. Les effets de bord sont des Listeners decouplés.
Configuration externalisee	Toute constante metier (commission %, rayon notif) dans platform_settings DB, modifiable sans deploiement.
API-Ready des le V1	Toutes les vues ont une couche Resource JSON correspondante. Migration mobile = zero refonte.
Migrations reversibles	Chaque migration a une methode down(). Rollback possible en cas de bug de deploiement.
Feature Flags	PlatformSetting::get('feature_fidelity') permet d'activer/desactiver des features sans deploiement.
Cache invalide proprement	Les Observers invalident le cache Redis des que les donnees changent. Jamais de TTL seul.


7.3 Variables d'Environnement (.env) Completes

# Application
APP_NAME=ConcertCM
APP_ENV=production
APP_KEY=base64:XXXX                    # php artisan key:generate
APP_DEBUG=false                         # TOUJOURS false en production
APP_URL=https://concertcm.cm
APP_TIMEZONE=Africa/Douala
APP_LOCALE=fr

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=concertcm_prod
DB_USERNAME=concertcm_user
DB_PASSWORD=xxxxxxxxxxxx

# Cache / Sessions / Queue -> Redis
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=xxxxxxxxxxxx

# Email
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.concertcm.cm
MAILGUN_SECRET=key-xxxxxxxxxx
MAIL_FROM_ADDRESS=noreply@concertcm.cm
MAIL_FROM_NAME=ConcertCM

# Paiement CinetPay
CINETPAY_API_KEY=xxxxxxxxxxxxxxxxxx
CINETPAY_SITE_ID=xxxxxxxxxx
CINETPAY_SECRET_KEY=xxxxxxxxxxxxxxxxxx
CINETPAY_NOTIFY_URL=https://concertcm.cm/webhook/cinetpay
CINETPAY_RETURN_URL=https://concertcm.cm/paiement/retour

# Stockage fichiers (S3 compatible OVH Object Storage)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=xxxxxxxxxxxx
AWS_SECRET_ACCESS_KEY=xxxxxxxxxxxx
AWS_DEFAULT_REGION=gra
AWS_BUCKET=concertcm-media
AWS_ENDPOINT=https://s3.gra.cloud.ovh.net
AWS_USE_PATH_STYLE_ENDPOINT=true

# Geocodage (Nominatim OpenStreetMap — gratuit)
NOMINATIM_URL=https://nominatim.openstreetmap.org
GEO_NOTIFICATION_RADIUS_KM=30

# Plateforme
PLATFORM_COMMISSION_RATE=0.04         # 4%
PLATFORM_COMMISSION_MIN=100            # 100 FCFA minimum




8. SYNTHESE & RECOMMANDATIONS


Ce document d'architecture constitue la reference technique contractuelle du projet ConcertCM. Il couvre l'integralite des aspects necessaires au demarrage immediat du developpement par une equipe technique.
8.1 Checklist de Demarrage du Projet

#	Action	Responsable	Duree
1	Cloner le repo Git, configurer .env.example	Dev Lead	2h
2	Executer : composer install + npm install + php artisan key:generate	Dev	30min
3	Configurer MySQL : creer base + user + droits	DBA	1h
4	Executer : php artisan migrate --seed	Dev	15min
5	Creer compte CinetPay sandbox + configurer .env	Chef Projet	1j
6	Configurer Redis + demarrer queue worker	DevOps	2h
7	Verifier envoi email (Mailgun sandbox)	Dev	1h
8	Premier deploiement staging + tests smoke	QA	1j


8.2 Points d'Attention Critiques

CRITIQUE — Anti-oversell billets
La methode TicketType::reserveTickets() avec SELECT FOR UPDATE est absolument non-negociable. Ne jamais bypass cette logique pour 'simplifier' le code. Un oversell = remboursements, perte de confiance, crise client.

CRITIQUE — Validation webhook CinetPay
Toujours verifier la signature HMAC du webhook avant de confirmer un billet. Un billet confirme sans paiement reel = perte financiere directe. La Route webhook doit exclure le middleware CSRF mais inclure la validation de signature custom.

IMPORTANT — Ne jamais stocker de cles en dur dans le code
Toutes les cles API (CinetPay, Mailgun, S3) doivent etre dans .env et jamais committees sur Git. Configurer .gitignore des le premier commit et ajouter un hook pre-commit de detection de secrets (git-secrets ou truffleHog).



ConcertCM — Document Architecture Systeme v1.0 — Avril 2025
Laravel 11 · PHP 8.2 · MySQL 8 · Redis 7 · Nginx · Bootstrap 5 · CinetPay
Document confidentiel — Propriete exclusive du projet ConcertCM — Reproduction interdite