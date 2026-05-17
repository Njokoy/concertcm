@extends('layouts.public')

@section('title', $concert->title . ' — ConcertCM')
@section('description', Str::limit($concert->description, 160))

@section('content')
<!-- Hero Concert -->
<div class="relative h-[60vh] md:h-[70vh] w-full overflow-hidden">
    <img class="absolute inset-0 w-full h-full object-cover scale-105" src="{{ $concert->poster ? asset($concert->poster) : 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1920' }}" alt="{{ $concert->title }}">
    <div class="hero-gradient absolute inset-0"></div>
    <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
        <div>
            <div class="flex items-center gap-3 mb-3">
                @if($concert->label)
                    <span class="bg-primary/20 text-primary border border-primary/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-extrabold">{{ $concert->label }}</span>
                @endif
                @if($concert->is_verified)
                    <span class="flex items-center gap-1 text-tertiary text-xs font-label font-bold"><span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>Certifié</span>
                @endif
            </div>
            <h1 class="text-4xl md:text-6xl font-headline font-extrabold tracking-tighter leading-none mb-4">{{ $concert->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-on-surface-variant text-sm">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-primary text-sm">location_on</span>{{ $concert->venue->name ?? '' }}, {{ $concert->venue->city ?? '' }}</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-secondary text-sm">calendar_month</span>{{ \Carbon\Carbon::parse($concert->date)->isoFormat('dddd D MMMM YYYY') }}</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-tertiary text-sm">schedule</span>{{ $concert->start_time ?? 'Voir programme' }}</span>
            </div>
        </div>
        <div class="text-right">
            <div class="text-2xl md:text-4xl font-headline font-black text-primary mb-1">{{ $concert->is_free ? 'Gratuit' : number_format($concert->price ?? 0).' FCFA' }}</div>
            @auth
                <button onclick="document.getElementById('tickets-section').scrollIntoView({behavior: 'smooth'})" class="primary-gradient-btn text-on-primary px-8 py-4 rounded-xl font-headline font-extrabold text-lg flex items-center gap-2 shadow-[0_20px_40px_rgba(255,145,87,0.4)] active:scale-95 transition-transform">
                    Voir les billets <span class="material-symbols-outlined">collections_bookmark</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="primary-gradient-btn text-on-primary px-8 py-4 rounded-xl font-headline font-extrabold text-lg flex items-center gap-2 shadow-[0_20px_40px_rgba(255,145,87,0.4)]">
                    Connexion pour réserver <span class="material-symbols-outlined">login</span>
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Concert Details -->
<div class="max-w-7xl mx-auto px-6 md:px-8 py-16 grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-10">
        <!-- Description -->
        <div>
            <h2 class="font-headline font-extrabold text-2xl mb-4 uppercase tracking-tight">À propos de cet événement</h2>
            <p class="text-on-surface-variant font-body leading-relaxed">{{ $concert->description }}</p>
        </div>

        <!-- Artists on lineup -->
        @if($concert->artists->count())
        <div>
            <h2 class="font-headline font-extrabold text-2xl mb-6 uppercase tracking-tight">Line-up</h2>
            <div class="flex flex-wrap gap-6">
                @foreach($concert->artists as $artist)
                <div class="flex items-center gap-4 bg-surface-container-highest p-4 rounded-2xl border border-outline-variant/10">
                    <img class="w-14 h-14 rounded-full object-cover border-2 border-primary"
                         src="{{ $artist->photo ?? 'https://ui-avatars.com/api/?name='.urlencode($artist->name).'&background=ff9157&color=fff' }}"
                         alt="{{ $artist->name }}">
                    <div>
                        <p class="font-headline font-bold">{{ $artist->name }}</p>
                        <p class="text-on-surface-variant text-[10px] font-label uppercase">{{ $artist->genre }}</p>
                        @if($artist->is_verified)
                            <span class="material-symbols-outlined text-tertiary text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Available Resource Types (Tickets) -->
        <div id="tickets-section">
            <h2 class="font-headline font-extrabold text-2xl mb-6 uppercase tracking-tight">Billets Disponibles</h2>
            
            @if(count($concert->resourceTypes))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($concert->resourceTypes as $resource)
                    <div class="bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant/10 flex flex-col group transition-transform hover:-translate-y-1 hover:shadow-2xl">
                        @if($resource->image_path)
                        <div class="h-40 overflow-hidden relative">
                            <img src="{{ asset($resource->image_path) }}" alt="{{ $resource->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        </div>
                        @else
                        <div class="h-40 bg-surface-container-highest flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-on-surface-variant/30">{{ $resource->category == 'stand' ? 'storefront' : ($resource->category == 'ticket' ? 'confirmation_number' : 'weekend') }}</span>
                        </div>
                        @endif
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-headline font-bold text-lg">{{ $resource->name }}</h3>
                                    <span class="px-3 py-1 bg-primary/20 text-primary text-xs rounded-full font-bold uppercase">{{ $resource->category }}</span>
                                </div>
                                <div class="text-xl font-black text-primary mb-4">{{ number_format($resource->price, 0, ',', ' ') }} FCFA</div>
                                <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-4">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                    <span>{{ $resource->available_quantity }} disponibles</span>
                                </div>
                            </div>
                            @auth
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="resource_type_id" value="{{ $resource->id }}">
                                <button class="w-full py-3 bg-primary text-on-primary font-bold rounded-xl hover:bg-primary/90 transition-colors uppercase tracking-widest text-xs">
                                    Réserver
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-surface-container-highest text-on-surface font-bold rounded-xl hover:bg-surface-variant transition-colors uppercase tracking-widest text-xs">
                                Connexion
                            </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-on-surface-variant italic">Tarification en cours de définition ou entrée libre.</p>
            @endif
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Ticket Info Card -->
        <div class="bg-surface-container-highest rounded-3xl p-8 border border-outline-variant/10">
            <h3 class="font-headline font-black text-lg uppercase mb-6">Infos Billet</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-on-surface-variant font-label">Prix</span>
                    <span class="font-bold text-primary">{{ $concert->is_free ? 'Gratuit' : number_format($concert->price ?? 0).' FCFA' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-on-surface-variant font-label">Capacité</span>
                    <span class="font-bold">{{ $concert->capacity ? number_format($concert->capacity) : '—' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-on-surface-variant font-label">Âge minimum</span>
                    <span class="font-bold">{{ $concert->min_age ? $concert->min_age.' ans' : 'Tout public' }}</span>
                </div>
                <hr class="border-outline-variant/10">
                <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-primary">qr_code_2</span>
                    QR Code envoyé par SMS après achat
                </div>
                <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-secondary">credit_card_off</span>
                    Orange Money & MTN MoMo
                </div>
            </div>
        </div>

        <!-- Venue Card -->
        @if($concert->venue)
        <div class="bg-surface-container-high rounded-3xl p-8 border border-outline-variant/10">
            <h3 class="font-headline font-black text-lg uppercase mb-4">Lieu</h3>
            <p class="font-bold mb-1">{{ $concert->venue->name }}</p>
            <p class="text-on-surface-variant text-sm font-body">{{ $concert->venue->address }}, {{ $concert->venue->city }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
