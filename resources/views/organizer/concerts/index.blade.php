@extends('layouts.app')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <header class="mb-12 flex justify-between items-end">
        <div>
            <h1 class="font-headline text-4xl font-black mb-2 italic">Mes Publications</h1>
            <p class="text-on-surface-variant font-label text-sm uppercase tracking-widest">Gérez vos concerts et événements</p>
        </div>
        <a href="{{ route('concerts.create') }}" class="primary-gradient-btn text-on-primary px-6 py-3 rounded-xl font-bold flex items-center gap-2">
            <span class="material-symbols-outlined">add_circle</span>
            Nouveau
        </a>
    </header>

    <div class="space-y-12">
        <!-- Section Concerts -->
        <div class="bg-surface-container-low rounded-3xl border border-outline-variant/10 overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-outline-variant/10 bg-surface-container-high/30">
                <h2 class="font-headline font-bold text-xl uppercase tracking-wider">Concerts de Musique</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] uppercase font-label text-on-surface-variant tracking-[0.2em] bg-surface-container-highest">
                        <tr>
                            <th class="p-4">Événement</th>
                            <th class="p-4">Lieu</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($concerts as $concert)
                            <tr class="hover:bg-surface-container-high/50 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-surface-container-highest">
                                            <img src="{{ $concert->poster }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm">{{ $concert->title }}</p>
                                            @if($concert->is_blocked)
                                                <span class="text-[8px] bg-error/10 text-error px-1.5 py-0.5 rounded-full uppercase font-black">Bloqué par l'admin</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-xs font-label opacity-70">{{ $concert->venue->name ?? 'CM' }}</td>
                                <td class="p-4 text-xs">{{ \Carbon\Carbon::parse($concert->date)->format('d M Y') }}</td>
                                <td class="p-4 text-xs uppercase font-black tracking-tighter opacity-50">{{ $concert->status }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('concerts.show', $concert->slug) }}" class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                        </a>
                                        <button class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-on-surface-variant italic">Aucun concert publié.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section Foires & Salons -->
        <div class="bg-surface-container-low rounded-3xl border border-outline-variant/10 overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-outline-variant/10 bg-surface-container-high/30">
                <h2 class="font-headline font-bold text-xl uppercase tracking-wider">Foires & Salons (EMS)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] uppercase font-label text-on-surface-variant tracking-[0.2em] bg-surface-container-highest">
                        <tr>
                            <th class="p-4">Événement</th>
                            <th class="p-4">Lieu</th>
                            <th class="p-4">Type</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($events as $event)
                            <tr class="hover:bg-surface-container-high/50 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-surface-container-highest font-headline font-black text-xs flex items-center justify-center text-on-surface-variant">EMS</div>
                                        <div>
                                            <p class="font-bold text-sm">{{ $event->title }}</p>
                                            @if($event->is_blocked)
                                                <span class="text-[8px] bg-error/10 text-error px-1.5 py-0.5 rounded-full uppercase font-black">Bloqué</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-xs font-label opacity-70">{{ $event->venue->name ?? 'CM' }}</td>
                                <td class="p-4 text-xs uppercase opacity-50">{{ $event->event_type }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('events.show', $event->slug) }}" class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                        </a>
                                        <button class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-on-surface-variant italic">Aucune foire ou salon publié.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
