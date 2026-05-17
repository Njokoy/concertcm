@extends('layouts.auth')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-4xl mx-auto w-full">
    <div class="mb-12">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-bold mb-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Retour au dashboard</span>
        </a>
        <h1 class="font-headline text-4xl font-extrabold tracking-tight">Modifier l'Événement</h1>
    </div>

    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-surface-container-low p-8 rounded-2xl border border-outline-variant/10 shadow-2xl">
        @csrf
        @method('PUT')
        
        <div class="space-y-2">
            <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Bannière / Affiche (Laissez vide pour conserver l'actuelle)</label>
            <input type="file" name="banner_image" class="w-full bg-surface-container-high border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-tertiary file:text-on-primary-fixed hover:file:bg-tertiary/90">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Titre</label>
                <input type="text" name="title" value="{{ $event->title }}" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
            </div>
            
            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Type d'Événement</label>
                <select name="event_type" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
                    <option value="fair" {{ $event->event_type == 'fair' ? 'selected' : '' }}>Foire / Salon</option>
                    <option value="cultural" {{ $event->event_type == 'cultural' ? 'selected' : '' }}>Festival Culturel</option>
                    <option value="other" {{ $event->event_type == 'other' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Lieu</label>
                <select name="venue_id" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ $event->venue_id == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Date de début</label>
                <input type="date" name="start_date" value="{{ $event->start_date->format('Y-m-d') }}" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Description</label>
            <textarea name="description" rows="4" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">{{ $event->description }}</textarea>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full py-4 bg-gradient-to-br from-tertiary to-tertiary-container text-on-primary-fixed rounded-xl font-black uppercase tracking-widest shadow-[0_20px_40px_rgba(87,192,255,0.3)] hover:scale-[1.02] transition-all duration-300">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
