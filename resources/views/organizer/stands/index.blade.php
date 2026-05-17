@extends('layouts.app')

@section('content')
<div class="pt-28 pb-16 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="font-headline text-4xl font-extrabold tracking-tight mb-2">Demandes de Stands</h1>
            <p class="text-on-surface-variant font-label uppercase tracking-widest text-xs">Gérez les espaces réservés pour vos événements</p>
        </div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-bold text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Retour au Dashboard
        </a>
    </header>

    @if(session('success'))
        <div class="bg-green-500/20 text-green-300 p-4 rounded-xl mb-8 font-bold flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-500/20 text-red-300 p-4 rounded-xl mb-8 font-bold flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-surface-container-low rounded-xl border border-outline-variant/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-high text-on-surface-variant text-xs uppercase tracking-widest">
                        <th class="p-6 font-semibold">Exposant</th>
                        <th class="p-6 font-semibold">Événement</th>
                        <th class="p-6 font-semibold">Ressource</th>
                        <th class="p-6 font-semibold">Montant</th>
                        <th class="p-6 font-semibold">Statut</th>
                        <th class="p-6 font-semibold">Date</th>
                        <th class="p-6 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-surface-container-highest/50 transition-colors">
                            <td class="p-6">
                                <div class="font-bold text-sm">{{ $booking->user->name }}</div>
                                <div class="text-xs text-on-surface-variant">{{ $booking->user->email }}</div>
                            </td>
                            <td class="p-6">
                                <div class="font-bold text-sm">{{ $booking->resourceType->resourceable->title }}</div>
                                <div class="text-xs text-primary uppercase">{{ class_basename($booking->resourceType->resourceable_type) }}</div>
                            </td>
                            <td class="p-6">
                                <div class="font-bold text-sm">{{ $booking->resourceType->name }}</div>
                                <div class="text-xs text-on-surface-variant">Qté: {{ $booking->quantity_booked ?? 1 }}</div>
                            </td>
                            <td class="p-6">
                                <div class="font-bold text-primary">{{ number_format($booking->total_price, 0, ',', ' ') }} FCFA</div>
                            </td>
                            <td class="p-6">
                                @if($booking->status == 'confirmed')
                                    <span class="px-2 py-1 bg-green-500/10 text-green-400 rounded text-xs font-bold uppercase">Confirmé</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="px-2 py-1 bg-red-500/10 text-red-400 rounded text-xs font-bold uppercase">Annulé/Refusé</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-500/10 text-yellow-400 rounded text-xs font-bold uppercase">En attente</span>
                                @endif
                            </td>
                            <td class="p-6 text-sm text-on-surface-variant">
                                {{ $booking->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-6 text-right">
                                <form action="{{ route('organizer.stands.status', $booking->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <select name="status" class="bg-surface-container-high border border-outline-variant/20 rounded-lg px-2 py-1 text-sm text-on-surface focus:outline-none focus:border-primary transition-colors" onchange="this.form.submit()">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmer</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Refuser</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-on-surface-variant italic">
                                Aucune demande de stand ou place n'a été trouvée pour vos événements.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
