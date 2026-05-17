<!DOCTYPE html>
<html class="{{ auth()->check() ? auth()->user()->theme : 'dark' }}" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ConcertCM — The Electric Pulse')</title>
    <meta name="description" content="@yield('description', 'Découvrez et réservez les meilleurs concerts et événements du Cameroun.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Be+Vietnam+Pro:wght@400;500;600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ff9157",
                        "primary-container": "#ff7a2c",
                        "on-primary": "#531e00",
                        "on-primary-fixed": "#000000",
                        "secondary": "#d97dff",
                        "secondary-container": "#721199",
                        "tertiary": "#81ecff",
                        "tertiary-container": "#00e3fd",
                        "surface": "#0e0e0e",
                        "surface-dim": "#0e0e0e",
                        "surface-bright": "#2c2c2c",
                        "surface-container": "#1a1919",
                        "surface-container-low": "#131313",
                        "surface-container-high": "#201f1f",
                        "surface-container-highest": "#262626",
                        "on-surface": "#ffffff",
                        "on-surface-variant": "#adaaaa",
                        "outline-variant": "#484847",
                        "outline": "#767575",
                        "background": "#0e0e0e",
                        "on-background": "#ffffff",
                        "error": "#ff7351",
                        "error-container": "#b92902",
                        "inverse-surface": "#fcf9f8",
                    },
                    fontFamily: {
                        headline: ["Plus Jakarta Sans"],
                        body: ["Be Vietnam Pro"],
                        label: ["Manrope"],
                    },
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .hero-gradient { background: linear-gradient(to top, #0e0e0e 0%, rgba(14,14,14,0.5) 50%, transparent 100%); }
        .card-gradient { background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 70%); }
        .primary-gradient-btn { background: linear-gradient(135deg, #ff9157 0%, #ff7a2c 100%); }
        .glass-nav { background: rgba(14,14,14,0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .glass-card { background: rgba(32,31,31,0.6); backdrop-filter: blur(12px); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeInUp 0.6s ease both; }
    </style>
    @yield('head')
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary/30 min-h-screen">

    <!-- ===================== NAVIGATION ===================== -->
    <nav class="glass-nav fixed top-0 w-full z-50 shadow-[0_20px_40px_rgba(0,0,0,0.4)]">
        <div class="flex justify-between items-center px-6 md:px-8 h-20 max-w-screen-2xl mx-auto">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-xl md:text-2xl font-black text-primary uppercase italic font-headline tracking-tight hover:opacity-80 transition-opacity">
                ConcertCM<span class="text-white">+</span>
            </a>

            <!-- Nav Links (Desktop) -->
            <div class="hidden md:flex items-center gap-8 lg:gap-12 font-headline font-bold tracking-tight">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary' }} transition-all duration-200">Accueil</a>
                <a href="{{ route('concerts.index') }}" class="{{ request()->routeIs('concerts.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary' }} transition-all duration-200">Concerts</a>
                <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'text-secondary border-b-2 border-secondary pb-1' : 'text-on-surface-variant hover:text-secondary' }} transition-all duration-200">Événements</a>
                <a href="#" class="text-on-surface-variant hover:text-tertiary transition-all duration-200">Artistes</a>
            </div>

            <!-- Auth Actions -->
            <div class="flex items-center gap-3 md:gap-4">
                <!-- Theme toggle (authenticated only) -->
                @auth
                    <button onclick="toggleTheme()" class="p-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors" title="Thème">
                        <span class="material-symbols-outlined text-xl">contrast</span>
                    </button>
                @endauth

                <!-- Search -->
                <button class="hidden md:flex p-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-xl">search</span>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="text-on-surface-variant hover:text-primary font-bold text-sm transition-colors hidden md:block">Connexion</a>
                    <a href="{{ route('register') }}" class="primary-gradient-btn text-on-primary px-5 py-2.5 rounded-xl font-headline font-bold text-sm transition-transform active:scale-95">
                        S'inscrire
                    </a>
                @else
                    <!-- Role badge -->
                    <span class="hidden md:block text-[10px] px-2 py-0.5 rounded-full font-label uppercase tracking-widest
                        {{ auth()->user()->hasRole('admin') ? 'bg-error/20 text-error' : (auth()->user()->hasRole('organizer') ? 'bg-secondary/20 text-secondary' : 'bg-primary/20 text-primary') }}">
                        {{ auth()->user()->getRoleNames()->first() ?? 'user' }}
                    </span>
                    <!-- Dashboard link -->
                    <a href="{{ route('dashboard') }}" class="hidden md:flex items-center gap-2 text-on-surface-variant hover:text-primary font-bold text-sm transition-colors">
                        <span class="material-symbols-outlined text-sm">dashboard</span>
                        Mon espace
                    </a>
                    <!-- Avatar -->
                    <a href="{{ route('profile.show') }}">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=ff9157&color=fff' }}" alt="Avatar" class="w-9 h-9 rounded-full border-2 border-primary object-cover hover:opacity-80 transition-opacity">
                    </a>
                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                        @csrf
                        <button class="p-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-sm">logout</span>
                        </button>
                    </form>
                @endguest

                <!-- Mobile menu button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-full bg-surface-container-high text-on-surface-variant">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-outline-variant/10 px-6 py-4 space-y-3 glass-nav">
            <a href="{{ url('/') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">home</span>Accueil
            </a>
            <a href="{{ route('concerts.index') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">mic</span>Concerts
            </a>
            <a href="{{ route('events.index') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:text-secondary hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">event</span>Événements
            </a>
            @guest
                <hr class="border-outline-variant/10">
                <a href="{{ route('login') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined">login</span>Connexion
                </a>
                <a href="{{ route('register') }}" class="block w-full primary-gradient-btn text-on-primary py-3 rounded-xl text-center font-bold">
                    S'inscrire
                </a>
            @else
                <hr class="border-outline-variant/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined">dashboard</span>Mon espace
                </a>
            @endguest
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-24 right-4 z-40 max-w-sm bg-surface-container-highest border border-tertiary/30 text-tertiary px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3" id="flashMsg">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="text-sm font-body">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-24 right-4 z-40 max-w-sm bg-surface-container-highest border border-error/30 text-error px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3" id="flashMsg">
            <span class="material-symbols-outlined">error</span>
            <span class="text-sm font-body">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-surface-container-low border-t border-outline-variant/10 py-16 px-6 md:px-8 mt-20">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="text-2xl font-black text-primary uppercase italic font-headline tracking-tight mb-4">ConcertCM<span class="text-white">+</span></div>
                    <p class="text-on-surface-variant text-sm font-body leading-relaxed max-w-xs">La plateforme de référence pour découvrir et vivre les meilleures expériences musicales au Cameroun.</p>
                    <div class="flex items-center gap-4 mt-6">
                        <span class="font-label text-xs uppercase tracking-widest text-on-surface-variant">Paiements acceptés :</span>
                        <span class="font-bold text-sm text-on-surface opacity-50">Orange Money</span>
                        <span class="font-bold text-sm text-on-surface opacity-50">MTN MoMo</span>
                    </div>
                </div>
                <div>
                    <p class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-6">Plateforme</p>
                    <nav class="space-y-3">
                        <a href="{{ route('concerts.index') }}" class="block text-sm text-on-surface-variant hover:text-primary transition-colors">Concerts</a>
                        <a href="{{ route('events.index') }}" class="block text-sm text-on-surface-variant hover:text-secondary transition-colors">Événements</a>
                        <a href="#" class="block text-sm text-on-surface-variant hover:text-tertiary transition-colors">Artistes</a>
                        @guest
                        <a href="{{ route('register') }}" class="block text-sm text-primary font-bold hover:opacity-80 transition-opacity">Rejoindre →</a>
                        @endguest
                    </nav>
                </div>
                <div>
                    <p class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-6">Informations</p>
                    <nav class="space-y-3">
                        <a href="{{ route('about') }}" class="block text-sm text-on-surface-variant hover:text-primary transition-colors">À propos</a>
                        <a href="{{ route('cgv') }}" class="block text-sm text-on-surface-variant hover:text-primary transition-colors">Conditions de vente</a>
                        <a href="{{ route('faq') }}" class="block text-sm text-on-surface-variant hover:text-primary transition-colors">Support & FAQ</a>
                        <a href="{{ route('faq') }}" class="block text-sm text-on-surface-variant hover:text-primary transition-colors">FAQ</a>
                    </nav>
                </div>
            </div>
            <div class="border-t border-outline-variant/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-on-surface-variant text-xs font-label uppercase tracking-widest opacity-50">© {{ date('Y') }} ConcertCM. Tous droits réservés.</p>
                @auth
                <span class="text-on-surface-variant text-xs font-label">Connecté en tant que <span class="text-primary font-bold">{{ auth()->user()->name }}</span></span>
                @endauth
            </div>
        </div>
    </footer>

    <!-- Mobile FAB (Book) -->
    @auth
    <a href="{{ route('concerts.index') }}" class="md:hidden fixed bottom-6 right-6 w-14 h-14 primary-gradient-btn text-on-primary rounded-full shadow-[0_20px_40px_rgba(255,145,87,0.4)] flex items-center justify-center z-40 active:scale-95 transition-transform">
        <span class="material-symbols-outlined">confirmation_number</span>
    </a>
    @endauth

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        // Auto-hide flash messages
        setTimeout(() => {
            document.getElementById('flashMsg')?.remove();
        }, 5000);

        // Theme toggle (auth + ajax)
        async function toggleTheme() {
            const res = await fetch('{{ route("theme.toggle") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            });
            const data = await res.json();
            document.documentElement.className = data.theme;
        }
    </script>
</body>
</html>
