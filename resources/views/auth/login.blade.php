@extends('layouts.auth')

@section('auth_content')
<main class="w-full max-w-[480px] z-10">
    <!-- Brand Identity -->
    <div class="text-center mb-8">
        <h1 class="font-headline font-black italic text-4xl tracking-tighter text-transparent bg-clip-text bg-gradient-to-br from-primary to-primary-container">
            ConcertCM
        </h1>
        <p class="font-label text-on-surface-variant uppercase tracking-[0.2em] text-xs mt-2">Accès Exclusif aux Scènes du Cameroun</p>
    </div>

    <!-- Glassmorphism Card -->
    <div class="glass-panel border border-outline-variant/15 rounded-[2.5rem] p-8 md:p-10 shadow-2xl">
        <!-- Custom Tabs -->
        <div class="flex p-1.5 bg-surface-container-low rounded-2xl mb-8">
            <a href="{{ route('login') }}" class="flex-1 py-3 px-4 rounded-xl font-headline font-bold text-sm text-center transition-all duration-300 {{ request()->routeIs('login') ? 'bg-surface-container-highest text-primary active-tab-glow' : 'text-on-surface-variant' }}">
                Connexion
            </a>
            <a href="{{ route('register') }}" class="flex-1 py-3 px-4 rounded-xl font-headline font-bold text-sm text-center transition-all duration-300 {{ request()->routeIs('register') ? 'bg-surface-container-highest text-primary active-tab-glow' : 'text-on-surface-variant' }}">
                Inscription
            </a>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div class="space-y-4">
                <div class="relative">
                    <label class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant ml-4 mb-1 block">Adresse Email</label>
                    <div class="flex items-center bg-surface-container-high rounded-2xl px-4 py-4 focus-within:ring-2 ring-primary/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-on-surface-variant mr-3">mail</span>
                        <input name="email" class="bg-transparent border-none focus:ring-0 w-full text-on-surface placeholder:text-outline text-sm" placeholder="nom@exemple.cm" type="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <p class="text-error text-xs mt-1 ml-4">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant ml-4 mb-1 block">Mot de passe</label>
                    <div class="flex items-center bg-surface-container-high rounded-2xl px-4 py-4 focus-within:ring-2 ring-primary/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-on-surface-variant mr-3">lock</span>
                        <input name="password" class="bg-transparent border-none focus:ring-0 w-full text-on-surface placeholder:text-outline text-sm" placeholder="••••••••" type="password" required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a class="font-label text-xs font-bold text-secondary hover:text-secondary-fixed transition-colors" href="#">Mot de passe oublié ?</a>
            </div>

            <button class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-headline font-extrabold text-base rounded-2xl shadow-lg hover:brightness-110 active:scale-[0.98] transition-all" type="submit">
                Se connecter
            </button>
        </form>
    </div>
</main>
@endsection
