@extends('layouts.public')

@section('title', 'Concerts — ConcertCM')
@section('description', 'Retrouvez tous les concerts au Cameroun et réservez vos billets.')

@section('content')
<div class="pt-28 pb-20 px-6 md:px-8 max-w-7xl mx-auto">
    @if(isset($recentConcerts) && count($recentConcerts))
    <!-- Hero Slider -->
    <div class="relative w-full overflow-hidden rounded-3xl h-[60vh] md:h-[70vh] mb-12 flex items-center justify-center bg-surface-container-highest group">
        <div id="concert-slider" class="flex overflow-x-auto snap-x snap-mandatory hide-scroll-bar w-full h-full scroll-smooth">
            @foreach($recentConcerts as $recent)
            <div class="snap-start shrink-0 w-full h-full relative">
                <img class="absolute inset-0 w-full h-full object-cover scale-105" src="{{ $recent->poster ? asset($recent->poster) : 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1920' }}" alt="{{ $recent->title }}">
                <div class="hero-gradient absolute inset-0"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16 max-w-7xl mx-auto flex flex-col justify-end gap-6 h-full">
                    <div class="w-full">
                        <span class="bg-primary/20 text-primary border border-primary/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-extrabold mb-3 inline-block">Nouveau Vibe</span>
                        <h2 class="text-4xl md:text-6xl font-headline font-extrabold tracking-tighter leading-none mb-4 text-white"><a href="{{ route('concerts.show', $recent->slug) }}" class="hover:underline">{{ $recent->title }}</a></h2>
                        <div class="flex flex-wrap items-center gap-4 text-white/80 text-sm">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">calendar_month</span>{{ \Carbon\Carbon::parse($recent->date)->isoFormat('dddd D MMM YYYY') }}</span>
                            @if($recent->venue)
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">location_on</span>{{ $recent->venue->city }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Navigation Buttons -->
        @if(count($recentConcerts) > 1)
        <button onclick="scrollSlider('concert-slider', -1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-md transition-all shadow-lg opacity-0 group-hover:opacity-100">
            <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button onclick="scrollSlider('concert-slider', 1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-md transition-all shadow-lg opacity-0 group-hover:opacity-100">
            <span class="material-symbols-outlined">chevron_right</span>
        </button>
        @endif
    </div>

    <!-- Script for slider -->
    <script>
        function scrollSlider(id, direction) {
            const slider = document.getElementById(id);
            const scrollAmount = slider.clientWidth;
            slider.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
        }
    </script>
    <style>
        .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }
        .hide-scroll-bar::-webkit-scrollbar { display: none; }
    </style>
    @else
    <header class="mb-12">
        <h1 class="text-4xl md:text-6xl font-headline font-extrabold tracking-tighter mb-4 uppercase italic">
            Tous les <span class="text-primary">Concerts</span>
        </h1>
        <p class="text-on-surface-variant font-body">{{ $concerts->total() }} concerts disponibles sur la plateforme.</p>
    </header>
    @endif

    <!-- Filters -->
    <form method="GET" class="flex flex-wrap gap-3 mb-12">
        <select name="city" class="bg-surface-container border border-outline-variant/20 rounded-xl px-4 py-2 text-sm font-label outline-none focus:ring-2 focus:ring-primary">
            <option value="">Toutes les villes</option>
            <option value="Douala" {{ request('city') === 'Douala' ? 'selected' : '' }}>Douala</option>
            <option value="Yaoundé" {{ request('city') === 'Yaoundé' ? 'selected' : '' }}>Yaoundé</option>
        </select>
        <button class="primary-gradient-btn text-on-primary px-6 py-2 rounded-xl font-bold text-sm">Filtrer</button>
        @if(request()->hasAny(['city','genre','date']))
            <a href="{{ route('concerts.index') }}" class="px-6 py-2 rounded-xl border border-outline-variant/20 text-on-surface-variant text-sm font-label hover:bg-surface-container transition-colors">Effacer</a>
        @endif
    </form>

    @if($concerts->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($concerts as $concert)
        <a href="{{ route('concerts.show', ['slug' => $concert->slug]) }}" class="group relative overflow-hidden rounded-2xl bg-surface-container-high h-80 block hover:shadow-2xl transition-shadow">
            <img class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                 src="{{ $concert->poster ?? 'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&w=600' }}"
                 alt="{{ $concert->title }}">
            <div class="card-gradient absolute inset-0"></div>

            @if($concert->label)
                <div class="absolute top-4 left-4">
                    <span class="bg-tertiary/20 text-tertiary border border-tertiary/20 backdrop-blur-md px-2 py-0.5 rounded-full text-[10px] font-label font-extrabold uppercase">{{ $concert->label }}</span>
                </div>
            @endif

            <div class="absolute bottom-0 p-6 w-full">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-primary text-[10px] font-label font-bold uppercase">{{ $concert->venue->city ?? 'CM' }}</span>
                    <span class="text-white/40 text-[10px]">•</span>
                    <span class="text-white/60 text-[10px] font-label">{{ \Carbon\Carbon::parse($concert->date)->format('d M Y') }}</span>
                </div>
                <h3 class="text-xl font-headline font-extrabold text-white leading-tight mb-3">{{ $concert->title }}</h3>
                <div class="flex justify-between items-center">
                    <span class="text-primary font-headline font-black">{{ $concert->is_free ? 'Gratuit' : number_format($concert->price ?? 0).' FCFA' }}</span>
                    @if($concert->is_verified)
                        <span class="material-symbols-outlined text-tertiary" style="font-variation-settings: 'FILL' 1; font-size:18px;">verified</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-12">
        {{ $concerts->links() }}
    </div>
    @else
    <div class="text-center py-32 text-on-surface-variant">
        <span class="material-symbols-outlined text-8xl mb-6 block opacity-10">music_off</span>
        <h2 class="text-2xl font-headline font-bold mb-2">Aucun concert trouvé</h2>
        <p class="text-sm">Modifiez vos filtres pour voir plus de résultats.</p>
    </div>
    @endif
</div>
@endsection
