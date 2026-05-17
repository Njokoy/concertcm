@extends('layouts.public')

@section('title', 'Événements — ConcertCM')

@section('content')
<div class="pt-28 pb-20 px-6 md:px-8 max-w-7xl mx-auto">
    @if(isset($recentEvents) && count($recentEvents))
    <!-- Hero Slider -->
    <div class="relative w-full overflow-hidden rounded-3xl h-[60vh] md:h-[70vh] mb-12 flex items-center justify-center bg-surface-container-highest group">
        <div id="event-slider" class="flex overflow-x-auto snap-x snap-mandatory hide-scroll-bar w-full h-full scroll-smooth">
            @foreach($recentEvents as $recent)
            <div class="snap-start shrink-0 w-full h-full relative">
                <img class="absolute inset-0 w-full h-full object-cover scale-105" src="{{ $recent->banner ? asset($recent->banner) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1920' }}" alt="{{ $recent->title }}">
                <div class="hero-gradient absolute inset-0"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16 max-w-7xl mx-auto flex flex-col justify-end gap-6 h-full">
                    <div class="w-full">
                        <span class="bg-secondary/20 text-secondary border border-secondary/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-extrabold mb-3 inline-block">À ne pas manquer</span>
                        <h2 class="text-4xl md:text-6xl font-headline font-extrabold tracking-tighter leading-none mb-4 text-white"><a href="{{ route('events.show', $recent->slug) }}" class="hover:underline">{{ $recent->title }}</a></h2>
                        <div class="flex flex-wrap items-center gap-4 text-white/80 text-sm">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">calendar_month</span>{{ \Carbon\Carbon::parse($recent->start_date)->isoFormat('dddd D MMM YYYY') }}</span>
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
        @if(count($recentEvents) > 1)
        <button onclick="scrollSlider('event-slider', -1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-md transition-all shadow-lg opacity-0 group-hover:opacity-100">
            <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button onclick="scrollSlider('event-slider', 1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-md transition-all shadow-lg opacity-0 group-hover:opacity-100">
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
            Nos <span class="text-secondary">Événements</span>
        </h1>
        <p class="text-on-surface-variant font-body">Foires, expositions, événements professionnels au Cameroun.</p>
    </header>
    @endif

    @if(isset($events) && count($events))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
        <a href="{{ route('events.show', $event->slug) }}" class="group relative bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant/10 hover:shadow-2xl transition-shadow block">
            <div class="h-48 relative overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                     src="{{ $event->banner ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600' }}"
                     alt="{{ $event->title }}">
                <div class="card-gradient absolute inset-0"></div>
                @if($event->label)
                    <span class="absolute top-3 left-3 bg-secondary/20 text-secondary border border-secondary/20 backdrop-blur-md px-2 py-0.5 rounded-full text-[10px] font-label font-extrabold uppercase">{{ $event->label }}</span>
                @endif
            </div>
            <div class="p-6">
                <p class="text-on-surface-variant text-[10px] font-label uppercase tracking-widest mb-2">{{ $event->event_type }}</p>
                <h3 class="font-headline font-extrabold text-lg leading-tight mb-2">{{ $event->title }}</h3>
                <div class="flex items-center gap-2 text-on-surface-variant text-xs font-label">
                    <span class="material-symbols-outlined text-sm text-secondary">calendar_month</span>
                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                    @if($event->venue)
                        <span>•</span><span>{{ $event->venue->city }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-32 text-on-surface-variant">
        <span class="material-symbols-outlined text-8xl mb-6 block opacity-10">event_busy</span>
        <h2 class="text-2xl font-headline font-bold mb-2">Aucun événement trouvé</h2>
    </div>
    @endif
</div>
@endsection
