@extends('layouts.auth')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-4xl mx-auto w-full">
    <div class="mb-12">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-bold mb-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Retour au dashboard</span>
        </a>
        <h1 class="font-headline text-4xl font-extrabold tracking-tight">Modifier le Concert</h1>
    </div>

    <form action="{{ route('concerts.update', $concert->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-surface-container-low p-8 rounded-2xl border border-outline-variant/10 shadow-2xl">
        @csrf
        @method('PUT')
        
        <div class="space-y-2">
            <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Affiche (Laissez vide pour conserver l'actuelle)</label>
            <input type="file" name="poster" class="w-full bg-surface-container-high border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-primary file:text-on-primary hover:file:bg-primary/90">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Titre du Concert</label>
                <input type="text" name="title" value="{{ $concert->title }}" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Lieu</label>
                <select name="venue_id" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ $concert->venue_id == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Date</label>
                <input type="date" name="date" value="{{ $concert->date->format('Y-m-d') }}" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Capacité</label>
                <input type="number" name="capacity" value="{{ $concert->capacity }}" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Description</label>
            <textarea name="description" rows="4" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">{{ $concert->description }}</textarea>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary-fixed rounded-xl font-black uppercase tracking-widest shadow-[0_20px_40px_rgba(255,145,87,0.3)] hover:scale-[1.02] transition-all duration-300">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
