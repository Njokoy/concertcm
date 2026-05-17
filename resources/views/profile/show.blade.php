@extends('layouts.app')

@section('content')
<div class="pt-28 pb-20 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <!-- Header Section: User Profile & Hero -->
    <section class="relative mb-12 rounded-[2rem] overflow-hidden bg-surface-container-low p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
        <div class="relative group">
            <div class="w-40 h-40 md:w-56 md:h-56 rounded-full overflow-hidden border-4 border-primary/20 p-1">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=ff9157&color=fff&size=200' }}" 
                     alt="{{ $user->name }}" 
                     class="w-full h-full object-cover rounded-full">
            </div>
            <button class="absolute bottom-2 right-2 bg-primary p-3 rounded-full shadow-xl hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-on-primary">photo_camera</span>
            </button>
        </div>
        <div class="text-center md:text-left">
            <h1 class="font-headline text-5xl md:text-7xl font-extrabold tracking-tighter text-on-surface mb-2">{{ $user->name }}</h1>
            <div class="flex items-center justify-center md:justify-start gap-2 text-tertiary font-label uppercase tracking-widest text-sm font-bold">
                <span class="material-symbols-outlined text-sm">location_on</span>
                {{ $user->city ?? 'Non définie' }}
            </div>
            <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-4">
                <div class="bg-surface-container-high px-4 py-2 rounded-lg">
                    <span class="block text-primary text-xl font-bold">{{ $user->tickets->count() }}</span>
                    <span class="text-xs font-label text-on-surface-variant uppercase">Concerts</span>
                </div>
                <div class="bg-surface-container-high px-4 py-2 rounded-lg">
                    <span class="block text-secondary text-xl font-bold">{{ $user->followedArtists->count() }}</span>
                    <span class="text-xs font-label text-on-surface-variant uppercase">Artistes suivis</span>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Settings & Artists -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Tickets & Bookings -->
            <div class="bg-surface-container-low rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-8">
                    <span class="material-symbols-outlined text-primary">confirmation_number</span>
                    <h2 class="font-headline text-2xl font-bold">Mes Billets & Réservations</h2>
                </div>
                
                @if($user->tickets->isEmpty())
                    <p class="text-on-surface-variant italic">Vous n'avez pas encore de réservations.</p>
                @else
                    <div class="space-y-4">
                        @foreach($user->tickets as $ticket)
                            <div class="flex items-center justify-between p-4 bg-surface-container-high rounded-2xl border border-outline-variant/10">
                                <div>
                                    <h4 class="font-bold text-on-surface">{{ $ticket->concert->title }}</h4>
                                    <p class="text-sm text-on-surface-variant">{{ $ticket->concert->date->format('d M Y') }} - {{ $ticket->concert->venue->name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 bg-primary/20 text-primary rounded-full text-xs font-bold uppercase">{{ $ticket->type }}</span>
                                    <p class="text-xs text-on-surface-variant mt-1">#{{ $ticket->ticket_number }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Followed Artists Grid -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-tertiary">star</span>
                        <h2 class="font-headline text-2xl font-bold">Artistes suivis</h2>
                    </div>
                    <button class="text-tertiary font-label text-sm uppercase tracking-widest font-bold hover:underline">Voir tout</button>
                </div>
                
                @if($user->followedArtists->isEmpty())
                    <p class="text-on-surface-variant italic">Vous ne suivez aucun artiste pour le moment.</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($user->followedArtists as $artist)
                            <div class="bg-surface-container-high p-4 rounded-3xl text-center group transition-transform hover:-translate-y-2">
                                <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden">
                                    <img src="{{ $artist->photo }}" alt="{{ $artist->name }}" class="w-full h-full object-cover">
                                </div>
                                <h3 class="font-body font-bold text-sm mb-3">{{ $artist->name }}</h3>
                                <button class="text-xs font-label uppercase text-error font-bold py-1 px-3 border border-error/20 rounded-full hover:bg-error hover:text-on-error transition-colors">
                                    Se désabonner
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Personal Info Card -->
            <div class="bg-surface-container-low rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-8">
                    <span class="material-symbols-outlined text-primary">person_edit</span>
                    <h2 class="font-headline text-2xl font-bold">Modifier le Profil</h2>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="md:col-span-2 space-y-2">
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold">Photo de Profil</label>
                        <input type="file" name="avatar" class="w-full bg-surface-container-high border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-primary file:text-on-primary hover:file:bg-primary/90">
                    </div>
                    <div class="space-y-2">
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold">Nom Complet</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-surface-container-high border-none rounded-xl p-4 focus:ring-2 focus:ring-primary text-on-surface">
                    </div>
                    <div class="space-y-2">
                        <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold">Ville</label>
                        <select name="city" class="w-full bg-surface-container-high border-none rounded-xl p-4 focus:ring-2 focus:ring-primary text-on-surface">
                            <option value="Douala" {{ $user->city == 'Douala' ? 'selected' : '' }}>Douala</option>
                            <option value="Yaoundé" {{ $user->city == 'Yaoundé' ? 'selected' : '' }}>Yaoundé</option>
                            <option value="Bafoussam" {{ $user->city == 'Bafoussam' ? 'selected' : '' }}>Bafoussam</option>
                            <option value="Garoua" {{ $user->city == 'Garoua' ? 'selected' : '' }}>Garoua</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="bg-primary text-on-primary font-bold py-4 px-8 rounded-xl hover:scale-105 transition-all">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Security & Preferences -->
        <div class="space-y-8">
            <!-- Preferences Section -->
            <div class="bg-surface-container-low rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-8">
                    <span class="material-symbols-outlined text-secondary">library_music</span>
                    <h2 class="font-headline text-2xl font-bold">Préférences</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    @php $prefs = $user->preferences ?? []; @endphp
                    @foreach(['Makossa', 'Bikutsi', 'Afrobeat', 'Hip-Hop', 'Gospel'] as $genre)
                        <span class="px-4 py-2 rounded-full {{ in_array($genre, $prefs) ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant' }} font-bold text-xs uppercase">
                            {{ $genre }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Security Card -->
            <div class="bg-surface-container-low rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-secondary">security</span>
                    <h2 class="font-headline text-xl font-bold">Sécurité</h2>
                </div>
                <div class="space-y-4">
                    <button class="w-full flex items-center justify-between p-4 bg-surface-container-high rounded-xl hover:bg-surface-variant transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary">lock_reset</span>
                            <span class="font-bold text-sm">Changer de mot de passe</span>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
                    </button>
                </div>
                <div class="mt-8 pt-6 border-t border-outline-variant/10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 text-error font-bold font-label uppercase text-sm tracking-widest hover:bg-error/10 rounded-xl transition-colors">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
