@extends('layouts.auth')

@section('content')
<main class="pt-32 pb-24 px-4 max-w-2xl mx-auto flex flex-col items-center">
    <!-- Success Message -->
    <div class="flex flex-col items-center mb-8 text-center">
        <div class="w-16 h-16 bg-tertiary/10 rounded-full flex items-center justify-center mb-4 border border-tertiary/20">
            <span class="material-symbols-outlined text-tertiary text-4xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
        <h1 class="font-headline font-bold text-2xl mb-1 text-on-surface">Félicitations, votre billet est prêt !</h1>
        <p class="text-on-surface-variant font-label text-sm tracking-wide uppercase">Préparez-vous pour une expérience inoubliable.</p>
    </div>

    <!-- The Ticket Card -->
    <div class="relative w-full bg-surface-container-highest/40 backdrop-blur-2xl rounded-[2.5rem] overflow-hidden shadow-2xl mb-10 border border-outline-variant/20 group">
        <!-- Visual Header -->
        <div class="h-48 relative overflow-hidden">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $ticket->concert->poster_path ?? 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=600' }}" alt="{{ $ticket->concert->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
            <div class="absolute bottom-6 left-8">
                <span class="bg-primary px-3 py-1 rounded-full text-on-primary font-label text-[10px] font-bold uppercase tracking-widest mb-2 inline-block">Concert</span>
                <h2 class="font-headline font-extrabold text-3xl text-white leading-tight uppercase tracking-tighter">{{ $ticket->concert->title }}</h2>
            </div>
        </div>

        <!-- Ticket Body -->
        <div class="p-8 space-y-8">
            <!-- QR Code Section -->
            <div class="flex flex-col items-center justify-center p-6 bg-white rounded-3xl mx-auto">
                <img src="{{ $ticket->qr_code_path }}" class="w-48 h-48" alt="Ticket QR Code">
                <p class="text-surface font-label text-[10px] font-bold mt-4 tracking-[0.2em] opacity-40">REF: {{ $ticket->reference }}</p>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-2 gap-y-6 gap-x-4 border-t border-outline-variant/10 pt-8">
                <div class="space-y-1">
                    <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest">Date & Heure</p>
                    <p class="font-body font-semibold text-sm">{{ \Carbon\Carbon::parse($ticket->concert->date)->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest">Lieu</p>
                    <p class="font-body font-semibold text-sm">{{ $ticket->concert->venue->name }}, {{ $ticket->concert->venue->city }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest">Prix</p>
                    <p class="font-body font-semibold text-sm text-primary">{{ number_format($ticket->price_paid, 0) }} FCFA</p>
                </div>
                <div class="space-y-1 text-right">
                    <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest">Statut</p>
                    <div class="flex items-center justify-end gap-1">
                        <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                        <p class="font-body font-semibold text-sm uppercase tracking-tighter text-tertiary">{{ $ticket->status }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="w-full space-y-4 mb-8">
        <a href="#" class="w-full bg-gradient-to-r from-primary to-primary-container text-on-primary font-headline font-bold py-5 rounded-2xl flex items-center justify-center gap-2 active:scale-95 transition-all shadow-lg">
            <span class="material-symbols-outlined">download</span>
            Télécharger le billet (PDF)
        </a>
        <a href="{{ route('dashboard') }}" class="w-full border border-outline-variant/30 text-on-surface font-headline font-bold py-5 rounded-2xl flex items-center justify-center gap-2 hover:bg-surface-container-high transition-colors active:scale-95">
            <span class="material-symbols-outlined">arrow_back</span>
            Retour au Dashboard
        </a>
    </div>

    <!-- Help / Info -->
    <div class="w-full bg-surface-container-low rounded-3xl p-6 border border-outline-variant/10">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-secondary">info</span>
            <h3 class="font-headline font-bold">Conseils pour l'entrée</h3>
        </div>
        <ul class="space-y-3">
            <li class="flex gap-3 items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-secondary mt-2 shrink-0"></span>
                <p class="text-on-surface-variant text-xs">Arrivez au moins 30 minutes avant le début du spectacle.</p>
            </li>
            <li class="flex gap-3 items-start">
                <span class="w-1.5 h-1.5 rounded-full bg-secondary mt-2 shrink-0"></span>
                <p class="text-on-surface-variant text-xs">Le QR code ne peut être scanné qu'une seule fois.</p>
            </li>
        </ul>
    </div>
</main>
@endsection
