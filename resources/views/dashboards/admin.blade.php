@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-surface">
    <!-- Sidebar -->
    <aside class="w-64 bg-surface-container-low border-r border-outline-variant/10 hidden lg:flex flex-col fixed h-full pt-20">
        <div class="p-6 space-y-8 flex-grow">
            <div>
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-4">Principal</p>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl bg-primary/10 text-primary font-bold">
                        <span class="material-symbols-outlined italic">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.events') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined">event</span>
                        <span>Événements</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined">group</span>
                        <span>Utilisateurs</span>
                    </a>
                </nav>
            </div>
            
            <div>
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-4">Modération</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined">verified</span>
                        <span>Artistes</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined">report</span>
                        <span>Signalements</span>
                    </a>
                </nav>
            </div>
        </div>
        
        <div class="p-6 border-t border-outline-variant/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary to-primary-container"></div>
                <div>
                    <p class="text-sm font-bold text-on-surface">Admin Root</p>
                    <p class="text-[10px] text-on-surface-variant uppercase tracking-tighter">Super Utilisateur</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow lg:ml-64 pt-28 pb-16 px-4 md:px-8">
        <header class="mb-12">
            <h1 class="font-headline text-4xl font-black mb-2 uppercase italic tracking-tighter">Administration Centrale</h1>
            <p class="text-on-surface-variant font-label text-sm uppercase tracking-widest">Aperçu global de l'écosystème ConcertCM+</p>
        </header>

        <!-- Stats Bento Grid -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10">
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-4">Utilisateurs</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-headline font-black text-primary">{{ number_format($stats['total_users']) }}</h3>
                    <span class="text-tertiary text-xs font-bold">+12%</span>
                </div>
            </div>
            <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10">
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-4">Concerts Actifs</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-headline font-black text-secondary">{{ number_format($stats['total_concerts']) }}</h3>
                    <span class="material-symbols-outlined text-secondary">mic_external_on</span>
                </div>
            </div>
            <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10">
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-4">Revenus Globaux</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-headline font-black text-tertiary">{{ number_format($stats['total_revenue'] / 1000000, 1) }}M <span class="text-sm">FCFA</span></h3>
                    <span class="material-symbols-outlined text-tertiary">payments</span>
                </div>
            </div>
            <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10">
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-4">Modération en attente</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-headline font-black text-error">{{ $stats['pending_concerts'] }}</h3>
                    <span class="material-symbols-outlined text-error">priority_high</span>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Charts / Recent Activity -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Chart Placeholder -->
                <div class="bg-surface-container-high p-8 rounded-[2.5rem] border border-outline-variant/20 h-96 flex flex-col justify-center items-center text-center">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4">monitoring</span>
                    <h4 class="font-headline font-bold text-on-surface-variant">Graphique d'activité en temps réel</h4>
                    <p class="text-on-surface-variant/50 text-sm">Visualisation des ventes et inscriptions</p>
                </div>

                <!-- Recent Users -->
                <div class="bg-surface-container-low rounded-3xl border border-outline-variant/10 overflow-hidden">
                    <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center">
                        <h2 class="font-headline font-bold">Inscriptions Récentes</h2>
                        <a href="{{ route('admin.users') }}" class="text-primary text-xs font-bold uppercase tracking-widest">Voir tout</a>
                    </div>
                    <div class="divide-y divide-outline-variant/10">
                        @foreach($stats['recent_users'] as $user)
                            <div class="p-4 flex items-center justify-between group hover:bg-surface-container-high transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center font-bold text-primary">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold">{{ $user->name }}</p>
                                        <p class="text-[10px] text-on-surface-variant uppercase tracking-tighter">{{ $user->getRoleNames()->first() ?? 'Utilisateur' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-on-surface-variant uppercase tracking-widest">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Side Cards -->
            <div class="space-y-8">
                <div class="bg-gradient-to-br from-secondary to-secondary-container p-8 rounded-[2rem] text-on-surface shadow-[0_20px_40px_rgba(217,125,255,0.2)]">
                    <h3 class="font-headline font-black text-2xl mb-4 italic uppercase leading-tight">Optimisez la visibilité</h3>
                    <p class="text-sm opacity-80 mb-6">Attribuez des étiquettes aux événements vedettes pour booster les ventes.</p>
                    <a href="{{ route('admin.events') }}" class="inline-flex items-center gap-2 bg-surface px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest">Gérer les événements</a>
                </div>

                <div class="bg-surface-container-highest p-8 rounded-[2rem] border border-outline-variant/20">
                    <h3 class="font-headline font-bold text-lg mb-4">Serveur & Performance</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-on-surface-variant uppercase">CPU</span>
                            <span class="text-xs font-bold text-tertiary">24%</span>
                        </div>
                        <div class="w-full bg-surface-container-low h-1.5 rounded-full overflow-hidden">
                            <div class="bg-tertiary h-full w-[24%]"></div>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-xs text-on-surface-variant uppercase">Mémoire</span>
                            <span class="text-xs font-bold text-primary">62%</span>
                        </div>
                        <div class="w-full bg-surface-container-low h-1.5 rounded-full overflow-hidden">
                            <div class="bg-primary h-full w-[62%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
