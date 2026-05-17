@extends('layouts.app')

@section('title', 'Statistiques de ' . $model->title)

@section('content')
<div class="px-6 md:px-8 max-w-7xl mx-auto py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center hover:bg-surface-variant transition-colors group">
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-surface">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-black">{{ $model->title }}</h1>
            <p class="text-on-surface-variant font-label text-sm uppercase tracking-widest mt-1">Analyse détaillée du {{ $type }}</p>
        </div>
    </div>

    @php
        $totalItems = $model->resourceTypes->sum('total_quantity');
        $availableItems = $model->resourceTypes->sum('available_quantity');
        $soldItems = $totalItems - $availableItems;
        $globalPercentage = $totalItems > 0 ? ($soldItems / $totalItems) * 100 : 0;
        
        $totalRevenue = 0;
        foreach($model->resourceTypes as $res) {
            $sold = $res->total_quantity - $res->available_quantity;
            $totalRevenue += ($sold * $res->price);
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Billets Sold -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">confirmation_number</span>
                </div>
                <h3 class="font-headline font-bold text-on-surface-variant">Vendus</h3>
            </div>
            <div class="text-3xl font-black mb-1">{{ number_format($soldItems) }} <span class="text-sm font-normal text-on-surface-variant">/ {{ number_format($totalItems) }}</span></div>
            <p class="text-xs font-label text-on-surface-variant uppercase tracking-widest">Total des articles</p>
        </div>
        
        <!-- Percentage Sold -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-secondary/20 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">pie_chart</span>
                </div>
                <h3 class="font-headline font-bold text-on-surface-variant">Écoulement</h3>
            </div>
            <div class="text-3xl font-black mb-1">{{ number_format($globalPercentage, 1) }}%</div>
            <div class="w-full bg-surface-container-highest rounded-full h-1 mt-3">
                <div class="bg-secondary h-1 rounded-full" style="width: {{ $globalPercentage }}%"></div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-tertiary/20 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <h3 class="font-headline font-bold text-on-surface-variant">Revenu Estimé</h3>
            </div>
            <div class="text-3xl font-black mb-1 text-primary">{{ number_format($totalRevenue, 0, ',', ' ') }} <span class="text-sm font-normal">FCFA</span></div>
            <p class="text-xs font-label text-on-surface-variant uppercase tracking-widest">Basé sur les réservations</p>
        </div>
    </div>

    <!-- Detailed Resource Breakdown -->
    <h2 class="text-xl font-headline font-bold mb-6">Répartition par type d'article</h2>
    @if(count($model->resourceTypes))
    <div class="overflow-x-auto">
        <table class="w-full text-left bg-surface-container-low rounded-2xl shadow-xl overflow-hidden border border-outline-variant/10">
            <thead class="bg-surface-container-high/50 text-on-surface-variant text-xs font-label uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4">Nom</th>
                    <th class="px-6 py-4">Catégorie</th>
                    <th class="px-6 py-4">Prix</th>
                    <th class="px-6 py-4">Quantité Vendue</th>
                    <th class="px-6 py-4 text-center">Progression</th>
                    <th class="px-6 py-4 text-right">Revenu Brut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10 font-body">
                @foreach($model->resourceTypes as $resource)
                    @php
                        $sold = $resource->total_quantity - $resource->available_quantity;
                        $pct = $resource->total_quantity > 0 ? ($sold / $resource->total_quantity) * 100 : 0;
                        $rev = $sold * $resource->price;
                    @endphp
                    <tr class="hover:bg-surface-container-high/30 transition-colors">
                        <td class="px-6 py-4 font-bold">{{ $resource->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-surface-container-highest rounded text-[10px] uppercase font-bold tracking-widest">{{ $resource->category }}</span>
                        </td>
                        <td class="px-6 py-4">{{ number_format($resource->price, 0) }} FCFA</td>
                        <td class="px-6 py-4 font-bold">{{ number_format($sold) }} <span class="text-xs text-on-surface-variant">/ {{ number_format($resource->total_quantity) }}</span></td>
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-full bg-surface-container-highest rounded-full h-1.5 flex-1">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs font-bold w-8 text-right">{{ round($pct) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-primary">{{ number_format($rev, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-on-surface-variant italic">Cet événement n'a pas encore de ressources définies (Ni billets, ni stands).</p>
    @endif
</div>
@endsection
