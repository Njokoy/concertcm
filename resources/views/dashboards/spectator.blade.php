@extends('layouts.app')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="font-headline text-5xl font-extrabold tracking-tighter mb-2 text-on-surface">Espace <span class="text-primary tracking-tight">Utilisateur</span></h1>
            <p class="text-on-surface-variant font-body">Bienvenue, {{ $user->name }}. Retrouvez l'historique complet de vos activités, vos tickets en cours, et vos stands réservés.</p>
        </div>
        <div>
            <a href="{{ route('profile.show') }}" class="text-secondary font-label uppercase tracking-widest text-xs flex items-center gap-2 hover:opacity-70 transition-opacity">
                <span>Gérer mon profil</span>
                <span class="material-symbols-outlined text-sm">settings</span>
            </a>
        </div>
    </header>

    <!-- KPI Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div class="bg-surface-container-high rounded-3xl p-8 border border-outline-variant/10 shadow-lg flex items-center gap-6 group hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 rounded-2xl bg-primary/20 text-primary flex items-center justify-center group-hover:scale-110 transition-transform"><span class="material-symbols-outlined text-2xl">payments</span></div>
            <div>
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-1">Total Dépensé</p>
                <p class="text-3xl font-headline font-black">{{ number_format($stats['total_spent'], 0, ',', ' ') }} <span class="text-xs font-normal">FCFA</span></p>
            </div>
        </div>
        <div class="bg-surface-container-high rounded-3xl p-8 border border-outline-variant/10 shadow-lg flex items-center gap-6 group hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 rounded-2xl bg-secondary/20 text-secondary flex items-center justify-center group-hover:scale-110 transition-transform"><span class="material-symbols-outlined text-2xl">confirmation_number</span></div>
            <div>
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-1">Billets Valides</p>
                <p class="text-3xl font-headline font-black">{{ $stats['valid_tickets'] }}</p>
            </div>
        </div>
        <div class="bg-surface-container-high rounded-3xl p-8 border border-outline-variant/10 shadow-lg flex items-center gap-6 group hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 rounded-2xl bg-tertiary/20 text-tertiary flex items-center justify-center group-hover:scale-110 transition-transform"><span class="material-symbols-outlined text-2xl">storefront</span></div>
            <div>
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-1">Espaces & Stands</p>
                <p class="text-3xl font-headline font-black">{{ $stats['total_stands'] }}</p>
            </div>
        </div>
    </div>

    <!-- Active Tickets & Bookings -->
    <div class="space-y-16 mb-20">
        
        <!-- Valid Tickets (Concerts) -->
        <section>
            <h2 class="text-2xl font-headline font-extrabold mb-8 uppercase tracking-tighter flex items-center gap-3">
                <span class="w-2 h-8 bg-secondary rounded-full"></span> Billets Actifs
            </h2>
            @if(count($validTickets))
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                @foreach($validTickets as $ticket)
                    <article class="relative group">
                        <div class="bg-surface-container-high rounded-3xl overflow-hidden shadow-2xl transition-transform duration-300 hover:scale-[1.01] flex flex-col md:flex-row border border-outline-variant/10">
                            <div class="md:w-1/3 relative h-48 md:h-auto overflow-hidden">
                                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ $ticket->concert->poster ? asset($ticket->concert->poster) : 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=400' }}" alt="{{ $ticket->concert->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-surface-container-high via-transparent to-transparent opacity-60"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-label font-extrabold tracking-widest uppercase backdrop-blur-md 
                                        {{ $ticket->status === 'confirmed' ? 'bg-tertiary/20 text-tertiary border border-tertiary/30' : 'bg-yellow-500/20 text-yellow-500 border border-yellow-500/30' }}">
                                        {{ $ticket->status === 'confirmed' ? 'Valide' : $ticket->status }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex-1 p-6 md:p-8 flex flex-col justify-between relative">
                                <div class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-surface"></div>
                                <div class="space-y-4">
                                    <div class="space-y-1">
                                        <p class="text-primary font-label text-[10px] uppercase tracking-[0.2em] font-extrabold">{{ class_basename($ticket->concert) }}</p>
                                        <h3 class="text-xl font-headline font-bold text-on-surface leading-tight">{{ $ticket->concert->title }}</h3>
                                    </div>
                                    <div class="flex items-center gap-6">
                                        <div class="space-y-1">
                                            <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-wider">Date</p>
                                            <p class="font-body font-bold text-on-surface text-sm uppercase">{{ \Carbon\Carbon::parse($ticket->concert->date)->translatedFormat('d M Y') }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-wider">Lieu</p>
                                            <p class="font-body font-bold text-on-surface text-sm">{{ $ticket->concert->venue->city ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 pt-6 border-t border-outline-variant/30 flex items-center justify-between">
                                    <div class="space-y-1">
                                        <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-wider">Référence ({{ number_format($ticket->price_paid, 0) }} CFA)</p>
                                        <p class="font-label font-extrabold text-on-surface tracking-widest uppercase text-xs">{{ $ticket->reference }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @if($ticket->qr_code_path)
                                        <a href="{{ route('tickets.show', $ticket->uuid) }}" class="bg-white p-1 rounded-lg hover:shadow-lg transition-shadow">
                                            <img src="{{ $ticket->qr_code_path }}" class="w-12 h-12 grayscale brightness-0" alt="QR Link">
                                        </a>
                                        @endif
                                        @if($ticket->status === 'confirmed' && \Carbon\Carbon::parse($ticket->concert->date)->subDays(2)->isFuture())
                                            <form action="{{ route('tickets.cancel', $ticket->id) }}" method="POST" onsubmit="return confirm('Annuler cette réservation définitivement ?');">
                                                @csrf
                                                <button type="submit" class="text-error hover:text-error-dim transition-colors" title="Annuler ma réservation (Max 2 jours avant)">
                                                    <span class="material-symbols-outlined">cancel</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @else
            <div class="text-on-surface-variant border-l-4 border-outline-variant/30 pl-4">Aucun billet de concert à usage unique en cours.</div>
            @endif
        </section>

        <!-- Active Resource Bookings (Foires & Stands) -->
        <section>
            <h2 class="text-2xl font-headline font-extrabold mb-8 uppercase tracking-tighter flex items-center gap-3">
                <span class="w-2 h-8 bg-tertiary rounded-full"></span> Réservations d'espaces (En Cours)
            </h2>
            @if(count($activeBookings))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach($activeBookings as $booking)
                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/10 shadow-lg relative group">
                    <div class="absolute right-6 top-6">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant/20 group-hover:text-tertiary/40 transition-colors">event_seat</span>
                    </div>
                    <div class="mb-4">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-widest
                            {{ $booking->status == 'confirmed' ? 'bg-green-500/20 text-green-400' : ($booking->status == 'pending' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-red-500/20 text-red-500') }}">
                            {{ $booking->status }}
                        </span>
                    </div>
                    <h3 class="text-lg font-headline font-bold mb-1">{{ $booking->resourceType->resourceable->title }}</h3>
                    <p class="text-on-surface-variant text-xs uppercase tracking-widest font-label line-clamp-1 mb-4">{{ $booking->resourceType->name }}</p>
                    
                    <div class="flex justify-between items-end mt-6">
                        <div>
                            <p class="text-on-surface-variant text-[10px] uppercase font-label">Réf: {{ $booking->reference }}</p>
                            <p class="font-bold text-lg text-primary">{{ number_format($booking->total_price, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="text-right">
                            <p class="text-on-surface-variant text-[10px] uppercase font-label">Date (Création)</p>
                            <p class="text-sm font-bold">{{ $booking->created_at->format('d M') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-on-surface-variant border-l-4 border-outline-variant/30 pl-4">Aucune réservation de stand ou d'espace en cours de validité.</div>
            @endif
        </section>

        <!-- Expired / History Section -->
        <section>
            <h2 class="text-2xl font-headline font-extrabold mb-8 uppercase tracking-tighter flex items-center gap-3">
                <span class="w-2 h-8 bg-surface-container-highest rounded-full"></span> Historique & Expirés
            </h2>
            
            @if(count($pastTickets) || count($pastBookings))
            <div class="bg-surface-container-low rounded-2xl border border-outline-variant/10 overflow-hidden shadow-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-surface-container-high/50 text-xs font-label uppercase text-on-surface-variant">
                        <tr>
                            <th class="p-4">Type</th>
                            <th class="p-4">Événement</th>
                            <th class="p-4">Statut</th>
                            <th class="p-4">Date</th>
                            <th class="p-4 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach($pastTickets as $pt)
                        <tr class="hover:bg-surface-container-high/30 transition-colors opacity-50 hover:opacity-100">
                            <td class="p-4 font-bold text-primary">Billet</td>
                            <td class="p-4">{{ $pt->concert->title }}</td>
                            <td class="p-4 uppercase text-xs">{{ $pt->status }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($pt->concert->date)->format('d/m/Y') }}</td>
                            <td class="p-4 text-right font-bold">{{ number_format($pt->price_paid, 0) }} CFA</td>
                        </tr>
                        @endforeach
                        @foreach($pastBookings as $pb)
                        <tr class="hover:bg-surface-container-high/30 transition-colors opacity-50 hover:opacity-100">
                            <td class="p-4 font-bold text-tertiary">Stand / Espace</td>
                            <td class="p-4">{{ $pb->resourceType->resourceable->title }} ({{ $pb->resourceType->name }})</td>
                            <td class="p-4 uppercase text-xs">{{ $pb->status }}</td>
                            <td class="p-4">{{ $pb->created_at->format('d/m/Y') }}</td>
                            <td class="p-4 text-right font-bold">{{ number_format($pb->total_price, 0) }} CFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="text-on-surface-variant italic pl-4">Historique vide pour le moment.</div>
            @endif
        </section>

    </div>

    <!-- Recommendations -->
    <section>
        <h2 class="text-3xl font-headline font-extrabold mb-8 uppercase tracking-tighter">À découvrir prochainement</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($recommendations as $concert)
                <div class="group relative overflow-hidden rounded-2xl bg-surface-container-high aspect-[4/5] border border-outline-variant/10 shadow-xl">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $concert->poster ? asset($concert->poster) : 'https://images.unsplash.com/photo-1453090927415-5f45085b65c0?auto=format&fit=crop&w=400' }}" alt="{{ $concert->title }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/20 to-transparent"></div>
                    <div class="absolute bottom-0 p-6 w-full">
                        <p class="text-primary font-label text-[10px] uppercase font-black tracking-widest mb-1">Cameroun</p>
                        <h4 class="text-xl font-headline font-bold text-on-surface mb-4 leading-tight">{{ $concert->title }}</h4>
                        <a href="{{ route('concerts.show', $concert->slug) }}" class="block w-full py-2 text-center text-primary font-label text-xs font-bold uppercase tracking-widest border border-primary/30 rounded-xl bg-primary/10 hover:bg-primary hover:text-on-primary backdrop-blur-md transition-all">Accéder</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
