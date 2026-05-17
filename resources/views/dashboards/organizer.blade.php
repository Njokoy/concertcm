@extends('layouts.app')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <!-- Header Actions -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="font-headline text-4xl font-extrabold tracking-tight mb-2">Tableau de bord</h1>
            <p class="text-on-surface-variant font-label uppercase tracking-widest text-xs">Vue d'ensemble de vos performances ({{ auth()->user()->name }})</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-surface-container-high hover:bg-surface-container-highest text-on-surface py-3 px-6 rounded-xl font-bold transition-all duration-200 border border-outline-variant/15">
                <span class="material-symbols-outlined">qr_code_scanner</span>
                <span>Scanner</span>
            </button>
            <div class="flex gap-2">
                <a href="{{ route('concerts.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-gradient-to-br from-primary to-primary-container text-on-primary-fixed py-3 px-6 rounded-xl font-bold transition-all duration-200 hover:opacity-90 shadow-[0_10px_20px_rgba(255,145,87,0.2)]">
                    <span class="material-symbols-outlined text-[18px]">music_note</span>
                    <span class="text-sm">Nouveau Concert</span>
                </a>
                <a href="{{ route('events.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-gradient-to-br from-tertiary to-tertiary-container text-on-primary-fixed py-3 px-6 rounded-xl font-bold transition-all duration-200 hover:opacity-90 shadow-[0_10px_20px_rgba(87,192,255,0.2)]">
                    <span class="material-symbols-outlined text-[18px]">event</span>
                    <span class="text-sm">Nouveau Salon</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Stats Bento Grid -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Revenue Stat -->
        <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">payments</span>
            </div>
            <p class="font-label text-on-surface-variant text-xs uppercase tracking-widest mb-4">Revenus totaux</p>
            <h3 class="font-headline text-4xl font-black text-primary mb-1">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <span class="text-xl text-primary/70">FCFA</span></h3>
            <div class="flex items-center gap-2 text-tertiary text-sm font-label mt-4">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                <span>En progression</span>
            </div>
        </div>
        <!-- Tickets Stat -->
        <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
            </div>
            <p class="font-label text-on-surface-variant text-xs uppercase tracking-widest mb-4">Billets vendus</p>
            <h3 class="font-headline text-4xl font-black text-secondary mb-1">{{ number_format($stats['tickets_sold'], 0, ',', ' ') }}</h3>
            <p class="text-on-surface-variant text-sm font-label mt-4">Sur {{ number_format($stats['total_capacity'], 0, ',', ' ') }} places</p>
        </div>
        <!-- Events Stat -->
        <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">donut_large</span>
            </div>
            <p class="font-label text-on-surface-variant text-xs uppercase tracking-widest mb-4">Événements Actifs</p>
            <h3 class="font-headline text-4xl font-black text-tertiary mb-1">{{ $stats['active_events'] }}</h3>
            <div class="w-full bg-surface-container-high h-2 rounded-full mt-6 overflow-hidden">
                <div class="bg-tertiary h-full rounded-full" style="width: 75%"></div>
            </div>
        </div>
    </section>

    <!-- Scanner & Validation -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Manual Ticket validation -->
        <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/10">
            <h2 class="font-headline text-xl font-bold mb-4">Validation Manuelle de Billet</h2>
            <p class="text-on-surface-variant text-sm mb-6">Saisissez le code de référence du billet pour vérifier sa validité et autoriser l'accès.</p>
            
            @if(session('success'))
                <div class="bg-green-500/20 text-green-300 p-4 rounded-lg mb-4 text-sm font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/20 text-red-300 p-4 rounded-lg mb-4 text-sm font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('tickets.verify') }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" name="ticket_code" placeholder="Ex: EP-123-ABC" class="flex-grow bg-surface-container-high border border-outline-variant/20 rounded-xl px-4 text-on-surface focus:outline-none focus:border-primary transition-colors" required>
                <button type="submit" class="bg-primary text-on-primary-fixed px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-opacity whitespace-nowrap">
                    Vérifier
                </button>
            </form>
        </div>
        
        <!-- Stand Requests quick access -->
        <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/10 flex flex-col justify-center items-center text-center">
             <div class="w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-secondary text-3xl">storefront</span>
             </div>
             <h2 class="font-headline text-xl font-bold mb-2">Demandes de Stands & Espaces</h2>
             <p class="text-on-surface-variant text-sm mb-6">Consultez et validez les réservations d'espaces pour vos événements.</p>
             <a href="{{ route('organizer.stands.index') }}" class="bg-surface-container-highest text-on-surface px-6 py-3 rounded-xl font-bold border border-outline-variant/10 hover:border-secondary/50 transition-colors">
                Gérer les demandes
             </a>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-surface-container-low p-8 rounded-xl shadow-xl border border-outline-variant/10">
            <div class="flex justify-between items-center mb-10">
                <h2 class="font-headline text-xl font-bold">Évolution des ventes</h2>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-primary text-on-primary rounded-lg text-xs font-bold">7J</button>
                    <button class="px-3 py-1 bg-surface-container-high text-on-surface-variant rounded-lg text-xs font-bold">30J</button>
                </div>
            </div>
            <div class="h-64 flex items-end justify-between gap-4">
                @foreach([30, 50, 40, 80, 95, 70, 85] as $val)
                    <div class="w-full bg-primary/20 rounded-t-lg transition-all hover:bg-primary" style="height: {{ $val }}%"></div>
                @endforeach
            </div>
            <div class="flex justify-between mt-4 font-label text-[10px] text-on-surface-variant uppercase">
                <span>Lun</span><span>Mar</span><span>Mer</span><span>Jeu</span><span>Ven</span><span>Sam</span><span>Dim</span>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/10">
            <h2 class="font-headline text-xl font-bold mb-8">Flux d'activité</h2>
            <div class="space-y-6">
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-full bg-tertiary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-tertiary text-sm">check_circle</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Nouveau paiement reçu</p>
                        <p class="text-xs text-on-surface-variant">Il y a 2 minutes</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary text-sm">confirmation_number</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Billet VIP Vendu</p>
                        <p class="text-xs text-on-surface-variant">Il y a 15 minutes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Concerts List -->
    <section class="mt-12 bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/10 shadow-2xl">
        <div class="p-8 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-high/30">
            <h2 class="font-headline text-xl font-bold">Mes Concerts</h2>
            <div class="flex gap-4">
                <a href="{{ route('organizer.concerts.manage') }}" class="text-primary font-label text-sm uppercase tracking-widest hover:underline font-black">Gérer mes publications</a>
                <button class="text-on-surface-variant font-label text-sm uppercase tracking-widest hover:underline">Historique complet</button>
            </div>
        </div>
        <div class="divide-y divide-outline-variant/10">
            @forelse($concerts as $concert)
                <div onclick="window.location='{{ route('organizer.concert.stats', $concert->id) }}'" class="p-6 flex flex-col md:flex-row items-center gap-6 group hover:bg-surface-container-high/50 transition-colors cursor-pointer">
                    <div class="w-full md:w-40 h-24 rounded-lg overflow-hidden bg-surface-container-highest">
                        <img src="{{ $concert->poster }}" alt="{{ $concert->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-3 mb-1">
                            <h4 class="font-headline font-bold text-lg group-hover:text-primary transition-colors">{{ $concert->title }}</h4>
                            <span class="px-2 py-0.5 rounded bg-tertiary/10 text-tertiary text-[10px] font-label uppercase tracking-tighter">{{ $concert->status }}</span>
                        </div>
                        <p class="text-on-surface-variant text-sm font-label">{{ $concert->date->format('d M Y') }} • {{ $concert->venue->name }}</p>
                    </div>
                    <div class="flex gap-12 text-center md:text-left">
                        <div>
                            <p class="text-on-surface-variant text-[10px] uppercase mb-1">Articles</p>
                            <p class="font-bold">{{ $concert->resourceTypes->sum(fn($r) => $r->total_quantity - $r->available_quantity) }} / {{ $concert->resourceTypes->sum('total_quantity') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 relative z-10" onclick="event.stopPropagation()">
                        <a href="{{ route('concerts.edit', $concert->id) }}" class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-on-surface-variant italic">
                    Aucun concert créé pour le moment.
                </div>
            @endforelse
        </div>
    </section>

    <!-- My Events List -->
    <section class="mt-12 bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/10 shadow-2xl">
        <div class="p-8 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-high/30">
            <h2 class="font-headline text-xl font-bold">Mes Événements / Salons</h2>
        </div>
        <div class="divide-y divide-outline-variant/10">
            @forelse($events as $event)
                <div onclick="window.location='{{ route('organizer.event.stats', $event->id) }}'" class="p-6 flex flex-col md:flex-row items-center gap-6 group hover:bg-surface-container-high/50 transition-colors cursor-pointer">
                    <div class="w-full md:w-40 h-24 rounded-lg overflow-hidden bg-surface-container-highest">
                        <img src="{{ $event->banner }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-3 mb-1">
                            <h4 class="font-headline font-bold text-lg group-hover:text-secondary transition-colors">{{ $event->title }}</h4>
                            <span class="px-2 py-0.5 rounded bg-tertiary/10 text-tertiary text-[10px] font-label uppercase tracking-tighter">{{ $event->status }}</span>
                            <span class="px-2 py-0.5 rounded bg-surface border border-outline-variant/20 text-[10px] font-label uppercase">{{ $event->event_type }}</span>
                        </div>
                        <p class="text-on-surface-variant text-sm font-label">{{ $event->start_date->format('d M Y') }} • {{ $event->venue->city }}</p>
                    </div>
                    <div class="flex gap-12 text-center md:text-left">
                        <div>
                            <p class="text-on-surface-variant text-[10px] uppercase mb-1">Articles</p>
                            <p class="font-bold">{{ $event->resourceTypes->sum(fn($r) => $r->total_quantity - $r->available_quantity) }} / {{ $event->resourceTypes->sum('total_quantity') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 relative z-10" onclick="event.stopPropagation()">
                        <a href="{{ route('events.edit', $event->id) }}" class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-on-surface-variant italic">
                    Aucun événement créé pour le moment.
                </div>
            @endforelse
        </div>
    </section>
</div>

<script>
    // Theme toggle simulation helper
    function toggleTheme() {
        document.documentElement.classList.toggle('dark');
        // AJAX to save to user preference would go here
    }
</script>
@endsection
