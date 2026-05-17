@extends('layouts.auth')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-4xl mx-auto w-full">
    <div class="mb-12">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-bold mb-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Retour au dashboard</span>
        </a>
        <h1 class="font-headline text-4xl font-extrabold tracking-tight">Nouveau Concert</h1>
        <p class="text-on-surface-variant font-label uppercase tracking-widest text-xs mt-2">Préparez votre prochain événement électrique</p>
    </div>

    <form action="{{ route('concerts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-surface-container-low p-8 rounded-2xl border border-outline-variant/10 shadow-2xl">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Titre du Concert</label>
                <input type="text" name="title" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all" placeholder="Ex: Urban Vibes Douala">
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Lieu</label>
                <select name="venue_id" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}">{{ $venue->name }} ({{ $venue->city }})</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Date</label>
                <input type="date" name="date" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all">
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Capacité maximale</label>
                <input type="number" name="capacity" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all" placeholder="Ex: 5000">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Description</label>
            <textarea name="description" rows="4" required class="w-full bg-surface-container-high border-none rounded-xl py-4 px-6 text-on-surface focus:ring-2 focus:ring-primary transition-all" placeholder="Décrivez l'ambiance et les détails du concert..."></textarea>
        </div>

        <div class="space-y-4 border-t border-outline-variant/10 pt-8 mt-8 pb-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-headline font-bold text-lg">Configuration des Places & Stands</h3>
                    <p class="text-on-surface-variant text-xs mt-1">Définissez vos types de billets ou espaces d'exposition (ex: VIP, Stand Standard, etc.)</p>
                </div>
                <button type="button" onclick="addStandRow()" class="bg-surface-container-highest text-on-surface text-sm px-4 py-2 rounded-lg hover:border-primary border border-transparent transition-colors font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span> Ajouter
                </button>
            </div>
            
            <div id="stands-container" class="space-y-4">
                <!-- Ligne initiale -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-surface-container-high rounded-xl border border-outline-variant/10 relative">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Nom (ex: Billet Accès Jour 1)</label>
                        <input type="text" name="resources[0][name]" required class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Catégorie</label>
                        <select name="resources[0][category]" required class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                            <option value="ticket">Billet d'entrée</option>
                            <option value="stand">Stand / Espaces</option>
                            <option value="booth">Loge VIP</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Prix Unitaire (FCFA)</label>
                        <input type="number" name="resources[0][price]" required min="0" class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Quantité Total</label>
                        <input type="number" name="resources[0][quantity]" required min="1" class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Image d'identité</label>
                        <input type="file" name="resources[0][image]" class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-xs focus:ring-1 focus:ring-primary file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-primary file:text-on-primary-fixed hover:file:bg-primary/90">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black uppercase tracking-widest text-on-surface-variant">Affiche du Concert (Poster)</label>
            <div class="relative group cursor-pointer h-40 bg-surface-container-high rounded-xl border-2 border-dashed border-outline-variant/30 flex flex-col items-center justify-center hover:border-primary transition-all">
                <input type="file" name="poster" class="absolute inset-0 opacity-0 cursor-pointer">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:text-primary">upload_file</span>
                <span class="text-sm text-on-surface-variant mt-2">Cliquez ou glissez l'affiche ici</span>
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary-fixed rounded-xl font-black uppercase tracking-widest shadow-[0_20px_40px_rgba(255,145,87,0.3)] hover:scale-[1.02] transition-all duration-300">
                Créer l'événement
            </button>
        </div>
    </form>
</div>

<script>
    let standIndex = 1;
    function addStandRow() {
        const container = document.getElementById('stands-container');
        const html = `
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 p-4 bg-surface-container-high rounded-xl border border-outline-variant/10 relative pr-12">
                <button type="button" onclick="this.parentElement.remove()" class="absolute right-4 top-1/2 -translate-y-1/2 text-red-500 hover:text-red-400 p-2 rounded-full hover:bg-red-500/10 transition-colors">
                    <span class="material-symbols-outlined text-xl">delete</span>
                </button>
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Nom</label>
                    <input type="text" name="resources[${standIndex}][name]" required class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Catégorie</label>
                    <select name="resources[${standIndex}][category]" required class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                        <option value="ticket">Billet d'entrée</option>
                        <option value="stand">Stand / Espaces</option>
                        <option value="booth">Loge VIP</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Prix (FCFA)</label>
                    <input type="number" name="resources[${standIndex}][price]" required min="0" class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Quantité</label>
                    <input type="number" name="resources[${standIndex}][quantity]" required min="1" class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Image d'identité</label>
                    <input type="file" name="resources[${standIndex}][image]" class="w-full bg-surface-container border-none rounded-lg py-2 px-3 text-xs focus:ring-1 focus:ring-primary file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-primary file:text-on-primary-fixed hover:file:bg-primary/90">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        standIndex++;
    }
</script>
@endsection
