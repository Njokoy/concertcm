@extends('layouts.public')

@section('title', 'ConcertCM — Vivez la Musique Camerounaise')

@section('content')

{{-- ========== HERO ========== --}}
<section class="relative h-[90vh] min-h-[600px] w-full overflow-hidden">
    <div class="absolute inset-0">
        @if($heroEvent)
            <img class="w-full h-full object-cover scale-105" src="{{ $heroEvent->poster ?? 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=1920' }}" alt="{{ $heroEvent->title }}">
        @else
            <img class="w-full h-full object-cover scale-105" src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1920" alt="ConcertCM">
        @endif
        <div class="hero-gradient absolute inset-0"></div>
    </div>

    <div class="relative z-10 h-full flex flex-col justify-end pb-20 px-6 md:px-16 max-w-7xl mx-auto animate-in">
        <div class="mb-4 flex items-center gap-3">
            <span class="bg-primary/20 text-primary border border-primary/20 px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-extrabold">⚡ À l'affiche</span>
            @if($heroEvent)
                <span class="text-on-surface-variant text-sm font-label">• {{ $heroEvent->venue->city ?? 'Cameroun' }}</span>
            @endif
        </div>

        <h1 class="text-5xl md:text-7xl lg:text-8xl font-headline font-extrabold tracking-tighter leading-none mb-6 max-w-4xl">
            @if($heroEvent)
                {{ strtoupper($heroEvent->title) }}
            @else
                LA SCÈNE<br>CAMEROUNAISE
            @endif
        </h1>

        <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl mb-10 font-body leading-relaxed">
            @if($heroEvent)
                {{ Str::limit($heroEvent->description, 150) }}
            @else
                Découvrez les meilleurs concerts, artistes et événements du triangle national.
            @endif
        </p>

        <div class="flex flex-wrap gap-4">
            @if($heroEvent)
                <form action="{{ route('tickets.store', ['concert' => $heroEvent->id]) }}" method="POST">
                        Acheter un ticket
                        <span class="material-symbols-outlined">confirmation_number</span>
                    </button>
                </form>
                <a href="{{ route('concerts.show', ['slug' => $heroEvent->slug]) }}" class="bg-surface-container-high text-on-surface px-8 py-4 rounded-xl font-headline font-bold text-lg border border-outline-variant/20 hover:bg-surface-variant transition-colors">
                    En savoir plus
                </a>
            @else
                <a href="{{ route('concerts.index') }}" class="primary-gradient-btn text-on-primary px-8 py-4 rounded-xl font-headline font-extrabold text-lg flex items-center gap-2 shadow-[0_20px_40px_rgba(255,145,87,0.3)]">
                    Explorer les concerts
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
                @guest
                <a href="{{ route('register') }}" class="bg-surface-container-high text-on-surface px-8 py-4 rounded-xl font-headline font-bold text-lg border border-outline-variant/20 hover:bg-surface-variant transition-colors">
                    Créer un compte
                </a>
                @endguest
            @endif
        </div>
    </div>
</section>

{{-- ========== SEARCH & FILTER BAR ========== --}}
<section class="relative -mt-12 z-20 px-6 md:px-8">
    <form method="GET" action="{{ route('concerts.index') }}" class="max-w-6xl mx-auto bg-surface-container-highest/80 backdrop-blur-xl p-4 md:p-6 rounded-2xl shadow-2xl flex flex-col md:flex-row gap-4 items-center border border-outline-variant/10">
        <div class="w-full md:flex-1 flex items-center gap-3 px-4 py-3 bg-surface-container rounded-xl border border-outline-variant/10">
            <span class="material-symbols-outlined text-primary">location_on</span>
            <select name="city" class="bg-transparent border-none text-on-surface font-label w-full focus:ring-0 cursor-pointer outline-none text-sm">
                <option value="">Toutes les villes</option>
                <option value="Douala">Douala</option>
                <option value="Yaoundé">Yaoundé</option>
                <option value="Bafoussam">Bafoussam</option>
            </select>
        </div>
        <div class="w-full md:flex-1 flex items-center gap-3 px-4 py-3 bg-surface-container rounded-xl border border-outline-variant/10">
            <span class="material-symbols-outlined text-secondary">music_note</span>
            <select name="genre" class="bg-transparent border-none text-on-surface font-label w-full focus:ring-0 cursor-pointer outline-none text-sm">
                <option value="">Tous les genres</option>
                <option value="Makossa">Makossa</option>
                <option value="Afrobeat">Afrobeat</option>
                <option value="Bikutsi">Bikutsi</option>
                <option value="Gospel">Gospel</option>
            </select>
        </div>
        <div class="w-full md:flex-1 flex items-center gap-3 px-4 py-3 bg-surface-container rounded-xl border border-outline-variant/10">
            <span class="material-symbols-outlined text-tertiary">calendar_month</span>
            <input name="date" type="date" class="bg-transparent border-none text-on-surface font-label w-full focus:ring-0 placeholder:text-on-surface-variant outline-none text-sm">
        </div>
        <button type="submit" class="w-full md:w-auto primary-gradient-btn text-on-primary px-10 py-3 rounded-xl font-headline font-bold transition-transform active:scale-95">
            Filtrer
        </button>
    </form>
</section>

{{-- ========== CONCERTS À LA UNE ========== --}}
<section class="py-24 px-6 md:px-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-3xl md:text-5xl font-headline font-extrabold tracking-tight mb-2">Concerts à la une</h2>
            <p class="text-on-surface-variant font-body">Les événements les plus attendus du moment.</p>
        </div>
        <a href="{{ route('concerts.index') }}" class="hidden md:flex items-center gap-2 text-secondary font-label font-bold uppercase tracking-widest text-sm hover:opacity-70 transition-opacity">
            Voir tout <span class="material-symbols-outlined">arrow_forward</span>
        </a>
    </div>

    @if($concerts->count())
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($concerts as $i => $concert)
            <a href="{{ route('concerts.show', ['slug' => $concert->slug]) }}"
               class="{{ $i === 0 ? 'md:col-span-2 h-[400px]' : 'h-[300px]' }} relative group overflow-hidden rounded-2xl bg-surface-container-high block">
                <img class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                     src="{{ $concert->poster ?? 'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&w=800' }}"
                     alt="{{ $concert->title }}">
                <div class="card-gradient absolute inset-0"></div>
                
                {{-- Label badge --}}
                @if($concert->label)
                    <div class="absolute top-4 left-4">
                        <span class="bg-tertiary/20 text-tertiary border border-tertiary/20 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-label font-extrabold uppercase tracking-widest">{{ $concert->label }}</span>
                    </div>
                @endif
                @if($concert->is_verified)
                    <div class="absolute top-4 right-4">
                        <span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>
                    </div>
                @endif

                <div class="absolute bottom-0 p-6 md:p-8 w-full">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-primary/20 text-primary backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-label font-bold uppercase">{{ strtoupper($concert->venue->city ?? 'CM') }}</span>
                        <span class="text-white/60 text-xs font-label">{{ \Carbon\Carbon::parse($concert->date)->format('d M Y') }}</span>
                    </div>
                    <h3 class="{{ $i === 0 ? 'text-3xl' : 'text-xl' }} font-headline font-extrabold text-white mb-1 leading-tight">{{ $concert->title }}</h3>
                    <p class="text-white/70 font-body text-sm mb-4">{{ $concert->venue->name ?? '' }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-headline font-black text-lg">{{ number_format($concert->price ?? 0) }} FCFA</span>
                        <form action="{{ route('tickets.store', ['concert' => $concert->id]) }}" method="POST" onclick="event.stopPropagation()">
                            @auth
                            <button type="submit" class="bg-white/10 hover:bg-primary backdrop-blur-md text-white p-3 rounded-full transition-all hover:text-on-primary active:scale-95" title="Réserver">
                                <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="bg-white/10 hover:bg-primary backdrop-blur-md text-white p-3 rounded-full transition-all hover:text-on-primary" title="Connexion requise">
                                <span class="material-symbols-outlined text-sm">lock</span>
                            </a>
                            @endauth
                        </form>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-20 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl mb-4 block opacity-20">music_off</span>
        <p>Aucun concert disponible pour le moment.</p>
    </div>
    @endif
</section>

{{-- ========== ARTISTES POPULAIRES ========== --}}
@if($artists->count())
<section class="py-24 bg-surface-container-low border-y border-outline-variant/10">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="mb-12 text-center">
            <h2 class="text-3xl md:text-4xl font-headline font-extrabold tracking-tight mb-2">Artistes populaires</h2>
            <p class="text-on-surface-variant font-body">Les voix qui font vibrer le triangle national.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">
            @foreach($artists as $artist)
            <div class="text-center group cursor-pointer">
                <div class="relative w-full aspect-square mb-4 rounded-full overflow-hidden border-2 border-transparent group-hover:border-primary transition-all duration-300 shadow-lg">
                    <img alt="{{ $artist->name }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
                         src="{{ $artist->photo ?? 'https://ui-avatars.com/api/?name='.urlencode($artist->name).'&size=200&background=262626&color=ff9157' }}">
                    @if($artist->is_verified)
                        <div class="absolute bottom-0 right-0 w-7 h-7 bg-surface rounded-full flex items-center justify-center border-2 border-surface">
                            <span class="material-symbols-outlined text-tertiary text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                        </div>
                    @endif
                </div>
                <h4 class="font-headline font-bold text-sm lg:text-base">{{ $artist->name }}</h4>
                <p class="text-on-surface-variant text-[10px] font-label uppercase tracking-wider">{{ $artist->genre }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ========== CTA / PAYMENT SECTION ========== --}}
<section class="py-24 px-6 md:px-8">
    <div class="max-w-5xl mx-auto bg-gradient-to-br from-secondary-container to-surface-container-high rounded-[2rem] p-10 md:p-16 relative overflow-hidden border border-outline-variant/10">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/10 blur-[100px] pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1 text-center md:text-left">
                <p class="font-label text-xs uppercase tracking-widest text-primary mb-4">Expérience simplifiée</p>
                <h2 class="text-3xl md:text-4xl font-headline font-extrabold mb-4 leading-tight">Achetez vos tickets<br>en un clic</h2>
                <p class="text-on-surface-variant mb-8 font-body leading-relaxed">Paiement sécurisé via Orange Money et MTN Mobile Money. Recevez votre billet QR directement sur votre téléphone.</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <span class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-2 text-sm font-bold opacity-70">Orange Money</span>
                    <span class="bg-surface-container-highest border border-outline-variant/20 rounded-xl px-4 py-2 text-sm font-bold opacity-70">MTN MoMo</span>
                </div>
            </div>
            <div class="w-full md:w-auto">
                <div class="bg-surface p-8 rounded-2xl shadow-2xl max-w-sm mx-auto border border-outline-variant/10">
                    <p class="font-label font-bold text-xs uppercase tracking-widest text-primary mb-4">Newsletter</p>
                    <h4 class="text-xl font-headline font-bold mb-6 leading-tight">Soyez le premier informé</h4>
                    <div class="space-y-4">
                        <input class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-4 py-3 text-on-surface placeholder:text-on-surface-variant focus:ring-2 focus:ring-primary outline-none text-sm" placeholder="votre@email.com" type="email">
                        <button class="w-full primary-gradient-btn text-on-primary py-3 rounded-xl font-headline font-bold transition-transform active:scale-95">
                            S'abonner
                        </button>
                    </div>
                    @guest
                    <p class="text-center text-[10px] mt-4 text-on-surface-variant">Ou <a href="{{ route('register') }}" class="text-primary hover:underline font-bold">créez un compte</a> pour plus d'avantages</p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
