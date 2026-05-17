@extends('layouts.app')

@section('title', 'Authentification — ConcertCM+')

@section('content')
<div class="relative min-h-[80vh] flex items-center justify-center p-4 overflow-hidden">
    <!-- Background elements for auth -->
    <div class="fixed inset-0 z-[-1] overflow-hidden">
        <img alt="Concert Background" class="w-full h-full object-cover scale-110 blur-sm brightness-[0.25]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBi_3-oW8D6ZAd0HcVdOPJo2Jmhmo5Iqn0Iqdy3AEzbPHPlaA0UQg-G1NHLQXnzEhWewi-_pLS02uSUej9qX9yLHDNesXGUztN-AJmJsWcsxcREr7mc-rzdTtnjgd5PAZLS8HKunfoWwNHj_uOyE8EhM5rmULpJS8AX3_eoZx4BeIG0a_z3_To5E6QEQMvibRJPZ9Pl2K-vFB9KxEJroq1OM9Dgd-KywPK-Aafjz0vC8Bn2RuNM7-C3j1hIV9mEVxk8mPT0xyNXBZ8"/>
        <div class="absolute inset-0 bg-gradient-to-tr from-background via-transparent to-primary/10"></div>
    </div>

    <div class="w-full max-w-md animate-in">
        @yield('auth_content')
        {{-- fallback for views that still use @section('content') --}}
        @if(!View::hasSection('auth_content'))
            {{-- This allows existing auth views to work if they don't use auth_content section yet --}}
        @endif
    </div>
</div>
@endsection
