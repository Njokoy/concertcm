@extends('layouts.auth')

@section('content')
<main class="w-full max-w-[800px] z-10">
    <div class="glass-panel border border-outline-variant/15 rounded-[2.5rem] p-10 shadow-2xl text-center">
        <h2 class="font-headline font-black text-4xl mb-4">Bienvenue, {{ auth()->user()->name }} !</h2>
        <p class="text-on-surface-variant mb-8">Vous êtes connecté en tant que <span class="text-primary font-bold uppercase">{{ auth()->user()->getRoleNames()->first() }}</span>.</p>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="py-3 px-8 bg-surface-container-highest text-on-surface rounded-xl hover:bg-error/20 hover:text-error transition-all font-bold">
                Se déconnecter
            </button>
        </form>
    </div>
</main>
@endsection
