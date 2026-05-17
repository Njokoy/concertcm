@extends('layouts.public')
@section('title', 'À Propos — ConcertCM+')
@section('description', 'ConcertCM+ est la première plateforme de billetterie et promotion d\'événements musicaux au Cameroun. Découvrez notre mission, notre équipe et nos valeurs.')

@section('content')

<!-- Hero -->
<section class="relative min-h-[70vh] flex items-end overflow-hidden">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1920" alt="Concert Cameroun">
        <div class="absolute inset-0" style="background: linear-gradient(to top, #0e0e0e 0%, rgba(14,14,14,0.6) 60%, transparent 100%);"></div>
    </div>
    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 pb-20 pt-40">
        <span class="inline-block bg-primary/20 text-primary border border-primary/20 px-4 py-1.5 rounded-full text-xs font-label uppercase tracking-widest font-bold mb-6">Notre histoire</span>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-headline font-extrabold tracking-tighter leading-none mb-6 max-w-4xl">
            La scène camerounaise,<br><span class="text-primary italic">numérisée.</span>
        </h1>
        <p class="text-xl text-on-surface-variant max-w-2xl font-body leading-relaxed">
            ConcertCM+ est née d'une conviction simple : la musique camerounaise mérite une infrastructure moderne. Nous avons bâti la première plateforme de billetterie et promotion d'événements 100% dédiée au marché local.
        </p>
    </div>
</section>

<!-- Mission -->
<section class="py-24 px-6 md:px-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        <div>
            <span class="inline-block bg-secondary/10 text-secondary border border-secondary/20 px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-bold mb-6">Notre mission</span>
            <h2 class="text-4xl font-headline font-extrabold tracking-tighter mb-6 leading-tight">Combler le vide numérique<br>de l'industrie musicale</h2>
            <p class="text-on-surface-variant font-body leading-relaxed mb-6">
                Avant ConcertCM+, la billetterie au Cameroun était quasi-entièrement artisanale : guichets physiques, ticket men dans les rues, Mobile Money manuel. Aucune traçabilité, aucun billet sécurisé, aucune analytics pour les organisateurs.
            </p>
            <p class="text-on-surface-variant font-body leading-relaxed">
                Nous avons changé cela. ConcertCM+ offre aux <strong class="text-on-surface">organisateurs</strong> un tableau de bord complet, aux <strong class="text-on-surface">artistes</strong> une présence vérifiée, et aux <strong class="text-on-surface">fans</strong> une expérience de réservation fluide, sécurisée et locale.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            @foreach([
                ['value'=>'>500','label'=>'Concerts recensés / an','color'=>'primary'],
                ['value'=>'12M+','label'=>'Utilisateurs Mobile Money','color'=>'secondary'],
                ['value'=>'4','label'=>'Rôles & profils dédiés','color'=>'tertiary'],
                ['value'=>'100%','label'=>'Local & Made in Cameroun','color'=>'primary'],
            ] as $stat)
            <div class="bg-surface-container-high border border-outline-variant/10 rounded-3xl p-8 text-center">
                <div class="text-4xl font-headline font-black text-{{ $stat['color'] }} mb-2">{{ $stat['value'] }}</div>
                <div class="text-on-surface-variant text-xs font-label uppercase tracking-widest">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-24 bg-surface-container-low border-y border-outline-variant/10 px-6 md:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="inline-block bg-tertiary/10 text-tertiary border border-tertiary/20 px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-bold mb-4">Ce qui nous guide</span>
            <h2 class="text-4xl font-headline font-extrabold tracking-tighter">Nos valeurs</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'diversity_3','color'=>'primary','title'=>'Inclusion Locale','desc'=>"Nous construisons pour le Cameroun. Nos moyens de paiement, notre interface en français, nos genres musicaux — tout est pensé pour notre marché."],
                ['icon'=>'verified','color'=>'secondary','title'=>'Transparence & Confiance','desc'=>'QR Codes uniques, artistes certifiés, labellisation des événements vedettes. Chaque interaction sur la plateforme est traçable et sécurisée.'],
                ['icon'=>'celebration','color'=>'tertiary','title'=>'La Culture d\'abord','desc'=>'Du Makossa au Bikutsi, de l\'Afrobeat au Gospel — nous valorisons chaque genre de la scène camerounaise sans hiérarchie.'],
            ] as $v)
            <div class="bg-surface-container-highest rounded-3xl p-8 border border-outline-variant/10">
                <div class="w-14 h-14 rounded-2xl bg-{{ $v['color'] }}/10 flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-{{ $v['color'] }} text-2xl" style="font-variation-settings: 'FILL' 1;">{{ $v['icon'] }}</span>
                </div>
                <h3 class="font-headline font-extrabold text-xl mb-3">{{ $v['title'] }}</h3>
                <p class="text-on-surface-variant font-body text-sm leading-relaxed">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Platform Roles -->
<section class="py-24 px-6 md:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="inline-block bg-primary/10 text-primary border border-primary/20 px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-bold mb-4">Écosystème</span>
            <h2 class="text-4xl font-headline font-extrabold tracking-tighter">Pour chaque acteur de la scène</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['role'=>'Spectateur','icon'=>'person','color'=>'primary','features'=>['Achat de billets QR','Historique & gestion','Suivi d\'artistes','Annulation sous 48h']],
                ['role'=>'Organisateur','icon'=>'business_center','color'=>'secondary','features'=>['Création de concerts','Tableau de bord analytics','Gestion de la billetterie','Labellisation Vedette']],
                ['role'=>'Exposant','icon'=>'store','color'=>'tertiary','features'=>['Réservation de stands','Gestion des ressources','Suivi des commandes','Accès aux foires']],
                ['role'=>'Administrateur','icon'=>'admin_panel_settings','color'=>'error','features'=>['Modération globale','Vérification artistes','Statistiques générales','Gestion des utilisateurs']],
            ] as $r)
            <div class="bg-surface-container-high rounded-3xl border border-outline-variant/10 p-7">
                <div class="w-12 h-12 rounded-2xl bg-{{ $r['color'] }}/10 flex items-center justify-center mb-5">
                    <span class="material-symbols-outlined text-{{ $r['color'] }}" style="font-variation-settings: 'FILL' 1;">{{ $r['icon'] }}</span>
                </div>
                <h3 class="font-headline font-extrabold text-lg mb-4 text-{{ $r['color'] }}">{{ $r['role'] }}</h3>
                <ul class="space-y-2">
                    @foreach($r['features'] as $f)
                    <li class="flex items-center gap-2 text-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-xs text-{{ $r['color'] }}" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        {{ $f }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Join -->
<section class="py-24 px-6 md:px-8 bg-surface-container-low border-t border-outline-variant/10">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tighter mb-6 leading-tight">
            Rejoignez la révolution<br><span class="text-primary italic">musicale camerounaise</span>
        </h2>
        <p class="text-on-surface-variant font-body text-lg max-w-2xl mx-auto mb-10">Plus de 500 événements par an. Des artistes certifiés. Des billets sécurisés. Et une plateforme qui grandit avec vous.</p>
        <div class="flex flex-wrap justify-center gap-4">
            @guest
            <a href="{{ route('register') }}" class="primary-gradient-btn text-on-primary px-10 py-4 rounded-xl font-headline font-extrabold text-lg flex items-center gap-2 shadow-[0_20px_40px_rgba(255,145,87,0.3)]">
                Rejoindre ConcertCM+ <span class="material-symbols-outlined">arrow_forward</span>
            </a>
            @endguest
            <a href="{{ route('concerts.index') }}" class="bg-surface-container-highest border border-outline-variant/10 text-on-surface px-10 py-4 rounded-xl font-headline font-bold text-lg hover:bg-surface-container transition-colors">
                Explorer les concerts
            </a>
        </div>
        <!-- Social links -->
        <div class="flex justify-center gap-4 mt-12">
            @foreach([
                ['href'=>'#','svg'=>'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z','label'=>'Facebook'],
                ['href'=>'#','svg'=>'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z','label'=>'Instagram'],
                ['href'=>'#','svg'=>'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z','label'=>'YouTube'],
            ] as $s)
            <a href="{{ $s['href'] }}" class="w-12 h-12 rounded-2xl bg-surface-container-highest border border-outline-variant/10 flex items-center justify-center hover:bg-primary/10 hover:border-primary/20 transition-colors group" title="{{ $s['label'] }}">
                <svg class="w-5 h-5 fill-on-surface-variant group-hover:fill-primary transition-colors" viewBox="0 0 24 24"><path d="{{ $s['svg'] }}"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
