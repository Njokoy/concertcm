@extends('layouts.app')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <!-- Header Actions -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="font-headline text-4xl font-extrabold tracking-tight mb-2">Espace Exposant</h1>
            <p class="text-on-surface-variant font-label uppercase tracking-widest text-xs">Gestion de vos réservations ({{ auth()->user()->name }})</p>
        </div>
        <div class="flex gap-4 w-full md:w-auto">
            <a href="#" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-gradient-to-br from-secondary to-secondary-container text-on-surface py-3 px-6 rounded-xl font-bold transition-all duration-200 hover:opacity-90 shadow-[0_20px_40px_rgba(217,125,255,0.2)]">
                <span class="material-symbols-outlined">add_business</span>
                <span>Réserver un stand</span>
            </a>
        </div>
    </header>

    <!-- Stats Bento Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <!-- Bookings Stat -->
        <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">inventory_2</span>
            </div>
            <p class="font-label text-on-surface-variant text-xs uppercase tracking-widest mb-4">Réservations Actives</p>
            <h3 class="font-headline text-4xl font-black text-secondary mb-1">{{ $bookings->count() }}</h3>
            <p class="text-on-surface-variant text-sm font-label mt-4">Sur {{ $bookings->groupBy('event_id')->count() }} événements différents</p>
        </div>
        <!-- Total Cost Stat -->
        <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
            </div>
            <p class="font-label text-on-surface-variant text-xs uppercase tracking-widest mb-4">Investissement Total</p>
            <h3 class="font-headline text-4xl font-black text-primary mb-1">{{ number_format($bookings->sum('total_price'), 0, ',', ' ') }} <span class="text-xl">FCFA</span></h3>
            <p class="text-on-surface-variant text-sm font-label mt-4">Paiements validés</p>
        </div>
    </section>

    <!-- My Bookings List -->
    <section class="bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/10 shadow-2xl">
        <div class="p-8 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-high/30">
            <h2 class="font-headline text-xl font-bold">Mes Réservations de Stand</h2>
        </div>
        <div class="divide-y divide-outline-variant/10">
            @forelse($bookings as $booking)
                <div class="p-6 flex flex-col md:flex-row items-center gap-6 group hover:bg-surface-container-high/50 transition-colors">
                    <div class="w-full md:w-32 h-20 rounded-lg overflow-hidden bg-surface-container-highest flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-4xl">storefront</span>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-3 mb-1">
                            <h4 class="font-headline font-bold text-lg">{{ $booking->resourceType->name }}</h4>
                            <span class="px-2 py-0.5 rounded bg-tertiary/10 text-tertiary text-[10px] font-label uppercase tracking-tighter">{{ $booking->status }}</span>
                        </div>
                        <p class="text-on-surface-variant text-sm font-label">{{ $booking->event->title }} • {{ $booking->event->venue->city }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-on-surface-variant text-[10px] uppercase mb-1">Montant</p>
                        <p class="font-bold text-primary">{{ number_format($booking->total_price, 0) }} FCFA</p>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-on-surface-variant italic">
                    <span class="material-symbols-outlined text-6xl mb-4 opacity-20 block">search_off</span>
                    Vous n'avez aucune réservation en cours.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
