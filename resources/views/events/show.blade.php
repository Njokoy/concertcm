@extends('layouts.public')

@section('title', $event->title . ' — ConcertCM')
@section('description', Str::limit($event->description, 160))

@section('content')
<!-- Hero Event -->
<div class="relative h-[60vh] md:h-[70vh] w-full overflow-hidden">
    <img class="absolute inset-0 w-full h-full object-cover scale-105" src="{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1920' }}" alt="{{ $event->title }}">
    <div class="hero-gradient absolute inset-0"></div>
    <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="bg-tertiary/20 text-tertiary border border-tertiary/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-extrabold">{{ $event->event_type == 'fair' ? 'Foire / Salon' : ($event->event_type == 'cultural' ? 'Festival Culturel' : 'Événement') }}</span>
                @if($event->is_verified)
                    <span class="flex items-center gap-1 text-tertiary text-xs font-label font-bold"><span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>Certifié</span>
                @endif
            </div>
            <h1 class="text-4xl md:text-6xl font-headline font-extrabold tracking-tighter leading-none mb-4">{{ $event->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-on-surface-variant text-sm">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-primary text-sm">location_on</span>{{ $event->venue->name ?? '' }}, {{ $event->venue->city ?? '' }}</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-tertiary text-sm">calendar_month</span>{{ \Carbon\Carbon::parse($event->start_date)->isoFormat('dddd D MMM YYYY') }} @if($event->end_date && $event->end_date != $event->start_date) - {{ \Carbon\Carbon::parse($event->end_date)->isoFormat('D MMM YYYY') }} @endif</span>
            </div>
        </div>
        <div class="text-right">
            @auth
                <button class="primary-gradient-btn text-on-primary px-8 py-4 rounded-xl font-headline font-extrabold text-lg flex items-center gap-2 shadow-[0_20px_40px_rgba(87,192,255,0.4)] active:scale-95 transition-transform" onclick="document.getElementById('stands-section').scrollIntoView({behavior: 'smooth'})">
                    Voir les espaces <span class="material-symbols-outlined">storefront</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="primary-gradient-btn text-on-primary px-8 py-4 rounded-xl font-headline font-extrabold text-lg flex items-center gap-2 shadow-[0_20px_40px_rgba(87,192,255,0.4)]">
                    Connexion pour réserver <span class="material-symbols-outlined">login</span>
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Event Details -->
<div class="max-w-7xl mx-auto px-6 md:px-8 py-16 grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-10">
        <!-- Description -->
        <div>
            <h2 class="font-headline font-extrabold text-2xl mb-4 uppercase tracking-tight">À propos de l'événement</h2>
            <p class="text-on-surface-variant font-body leading-relaxed">{{ $event->description }}</p>
        </div>

        <!-- Available Resource Types (Stands, Tickets) -->
        <div id="stands-section">
            <h2 class="font-headline font-extrabold text-2xl mb-6 uppercase tracking-tight">Plans & Espaces Disponibles</h2>
            
            @if(count($event->resourceTypes))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($event->resourceTypes as $resource)
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
                                    <span class="px-3 py-1 bg-tertiary/20 text-tertiary text-xs rounded-full font-bold uppercase">{{ $resource->category }}</span>
                                </div>
                                <div class="text-xl font-black text-tertiary mb-4">{{ number_format($resource->price, 0, ',', ' ') }} FCFA</div>
                                <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-4">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                    <span>{{ $resource->available_quantity }} disponibles</span>
                                </div>
                            </div>
                            @auth
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="resource_type_id" value="{{ $resource->id }}">
                                <button class="w-full py-3 bg-tertiary text-on-primary font-bold rounded-xl hover:bg-tertiary/90 transition-colors uppercase tracking-widest text-xs">
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
                <p class="text-on-surface-variant italic">Aucun espace ou billet défini pour cet événement.</p>
            @endif
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Venue Card -->
        @if($event->venue)
        <div class="bg-surface-container-high rounded-3xl p-8 border border-outline-variant/10">
            <h3 class="font-headline font-black text-lg uppercase mb-4">Lieu de l'exposition</h3>
            <p class="font-bold mb-1">{{ $event->venue->name }}</p>
            <p class="text-on-surface-variant text-sm font-body mb-4">{{ $event->venue->address }}, {{ $event->venue->city }}</p>
            
            <div class="h-32 bg-surface-container-highest rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/50">map</span>
            </div>
        </div>
        @endif
        
        <div class="bg-surface-container-highest rounded-3xl p-8 border border-outline-variant/10">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-tertiary">support_agent</span>
                <h3 class="font-headline font-black text-lg uppercase">Contact Organisateur</h3>
            </div>
            <p class="text-sm text-on-surface-variant">Pour toute question concernant les stands, l'installation ou les loges, veuillez contacter directement l'organisateur.</p>
            <button class="mt-4 text-tertiary font-bold text-sm uppercase tracking-widest hover:underline flex items-center gap-1">
                Contacter <span class="material-symbols-outlined text-sm">open_in_new</span>
            </button>
        </div>
    </div>
</div>
@endsection
