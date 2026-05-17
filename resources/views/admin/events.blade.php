@extends('layouts.auth')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar (Shared with Admin Dashboard) -->
    <aside class="w-64 bg-surface-container-low border-r border-outline-variant/10 hidden lg:flex flex-col fixed h-full pt-20">
        <div class="p-6 space-y-8 flex-grow">
            <div>
                <p class="text-on-surface-variant font-label text-[10px] uppercase tracking-widest mb-4">Principal</p>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.events') }}" class="flex items-center gap-3 p-3 rounded-xl bg-secondary/10 text-secondary font-bold">
                        <span class="material-symbols-outlined italic">event</span>
                        <span>Événements</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined">group</span>
                        <span>Utilisateurs</span>
                    </a>
                </nav>
            </div>
        </div>
    </aside>

    <main class="flex-grow lg:ml-64 pt-28 pb-16 px-4 md:px-8">
        <header class="mb-12 flex justify-between items-end">
            <div>
                <h1 class="font-headline text-4xl font-black mb-2 italic">Modération des Événements</h1>
                <p class="text-on-surface-variant font-label text-sm uppercase tracking-widest">Attribuez des labels et gérez la visibilité</p>
            </div>
        </header>

        <section class="space-y-12">
            <!-- Concerts Management -->
            <div class="bg-surface-container-low rounded-3xl border border-outline-variant/10 overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-outline-variant/10 bg-surface-container-high/30">
                    <h2 class="font-headline font-bold text-xl uppercase tracking-wider">Concerts de Musique</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="text-[10px] uppercase font-label text-on-surface-variant tracking-[0.2em] bg-surface-container-highest">
                            <tr>
                                <th class="p-4">Titre</th>
                                <th class="p-4">Organisateur</th>
                                <th class="p-4">Lieu</th>
                                <th class="p-4">Label Actuel</th>
                                <th class="p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($concerts as $concert)
                                <tr class="hover:bg-surface-container-high/50 transition-colors">
                                    <td class="p-4">
                                        <p class="font-bold text-sm">{{ $concert->title }}</p>
                                        <p class="text-[10px] text-on-surface-variant italic">{{ $concert->slug }}</p>
                                    </td>
                                    <td class="p-4 text-xs">{{ $concert->organizer->name }}</td>
                                    <td class="p-4 text-xs font-label opacity-70">{{ $concert->venue->city }}</td>
                                    <td class="p-4">
                                        @if($concert->label)
                                            <span class="bg-tertiary/10 text-tertiary border border-tertiary/20 px-2 py-0.5 rounded text-[10px] uppercase font-black">{{ $concert->label }}</span>
                                        @else
                                            <span class="text-on-surface-variant opacity-30 italic text-[10px]">Aucun</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-4">
                                            <!-- Label Update -->
                                            <form action="{{ route('admin.events.label', ['type' => 'concert', 'id' => $concert->id]) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <select name="label" class="bg-surface border border-outline-variant text-[10px] rounded px-2 py-1 outline-none">
                                                    <option value="">Label</option>
                                                    <option value="Vedette" {{ $concert->label === 'Vedette' ? 'selected' : '' }}>Vedette</option>
                                                    <option value="À la une" {{ $concert->label === 'À la une' ? 'selected' : '' }}>À la une</option>
                                                    <option value="Certifié" {{ $concert->label === 'Certifié' ? 'selected' : '' }}>Certifié</option>
                                                </select>
                                                <button type="submit" class="bg-surface-container-highest p-1 rounded hover:bg-outline-variant transition-colors">
                                                    <span class="material-symbols-outlined text-sm">save</span>
                                                </button>
                                            </form>

                                            <!-- Block/Unblock -->
                                            <form action="{{ route('admin.events.block', ['type' => 'concert', 'id' => $concert->id]) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @if($concert->is_blocked)
                                                    <button type="submit" title="Débloquer" class="bg-success/10 text-success p-1 rounded hover:bg-success/20 transition-colors">
                                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                                    </button>
                                                @else
                                                    <input type="text" name="reason" placeholder="Raison..." class="bg-surface border border-outline-variant text-[10px] rounded px-2 py-1 outline-none w-20">
                                                    <button type="submit" title="Bloquer" class="bg-error/10 text-error p-1 rounded hover:bg-error/20 transition-colors">
                                                        <span class="material-symbols-outlined text-sm">block</span>
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant/10">
                    {{ $concerts->links() }}
                </div>
            </div>
            <!-- Generic Events Management -->
            <div class="bg-surface-container-low rounded-3xl border border-outline-variant/10 overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-outline-variant/10 bg-surface-container-high/30">
                    <h2 class="font-headline font-bold text-xl uppercase tracking-wider">Foires & Salons</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="text-[10px] uppercase font-label text-on-surface-variant tracking-[0.2em] bg-surface-container-highest">
                            <tr>
                                <th class="p-4">Titre</th>
                                <th class="p-4">Organisateur</th>
                                <th class="p-4">Type</th>
                                <th class="p-4">Label</th>
                                <th class="p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($events as $event)
                                <tr class="hover:bg-surface-container-high/50 transition-colors">
                                    <td class="p-4">
                                        <p class="font-bold text-sm">{{ $event->title }}</p>
                                        @if($event->is_blocked)
                                            <span class="text-[8px] bg-error/10 text-error px-1.5 py-0.5 rounded-full uppercase font-black">Bloqué</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs">{{ $event->organizer->name }}</td>
                                    <td class="p-4 text-xs font-label opacity-70">{{ $event->event_type }}</td>
                                    <td class="p-4">
                                        <span class="bg-tertiary/10 text-tertiary px-2 py-0.5 rounded text-[10px] uppercase font-black">{{ $event->label ?? 'Public' }}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-4">
                                            <form action="{{ route('admin.events.block', ['type' => 'event', 'id' => $event->id]) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @if($event->is_blocked)
                                                    <button type="submit" title="Débloquer" class="bg-success/10 text-success p-1 rounded hover:bg-success/20 transition-colors">
                                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                                    </button>
                                                @else
                                                    <input type="text" name="reason" placeholder="Raison..." class="bg-surface border border-outline-variant text-[10px] rounded px-2 py-1 outline-none w-20">
                                                    <button type="submit" title="Bloquer" class="bg-error/10 text-error p-1 rounded hover:bg-error/20 transition-colors">
                                                        <span class="material-symbols-outlined text-sm">block</span>
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-outline-variant/10">
                    {{ $events->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
