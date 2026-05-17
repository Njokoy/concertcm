@extends('layouts.public')
@section('title', 'Support & FAQ — ConcertCM+')
@section('description', 'Questions fréquentes et formulaire de contact ConcertCM+.')

@section('head')
<script>
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const icon = btn.querySelector('.faq-icon');
    const isOpen = !answer.classList.contains('hidden');
    document.querySelectorAll('.faq-answer').forEach(a => a.classList.add('hidden'));
    document.querySelectorAll('.faq-icon').forEach(i => i.textContent = 'add');
    if (!isOpen) { answer.classList.remove('hidden'); icon.textContent = 'remove'; }
}
</script>
@endsection

@section('content')
<section class="relative pt-32 pb-16 px-6 md:px-8 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 w-[600px] h-[400px] bg-secondary/5 blur-[120px] rounded-full -translate-x-1/2"></div>
    </div>
    <div class="max-w-3xl mx-auto text-center relative z-10">
        <span class="inline-block bg-secondary/10 text-secondary border border-secondary/20 px-4 py-1.5 rounded-full text-xs font-label uppercase tracking-widest font-bold mb-6">Support</span>
        <h1 class="text-4xl md:text-6xl font-headline font-extrabold tracking-tighter mb-6">
            Comment pouvons-nous<br><span class="text-secondary italic">vous aider ?</span>
        </h1>
        <div class="flex items-center gap-3 bg-surface-container-highest border border-outline-variant/20 rounded-2xl px-6 py-4 max-w-xl mx-auto mt-8">
            <span class="material-symbols-outlined text-on-surface-variant">search</span>
            <input type="text" id="faqSearch" placeholder="Rechercher une question..."
                   class="bg-transparent border-none text-on-surface placeholder:text-on-surface-variant flex-grow outline-none font-body text-sm"
                   oninput="document.querySelectorAll('.faq-item').forEach(i=>{ i.style.display = this.value===''||i.dataset.q.includes(this.value.toLowerCase())?'':'none' })">
        </div>
    </div>
</section>

<section class="pb-20 px-6 md:px-8">
    <div class="max-w-3xl mx-auto space-y-12">

        @php
        $categories = [
            ['icon'=>'confirmation_number','color'=>'primary','title'=>'Billets & Réservations','faqs'=>[
                ['q'=>'Comment acheter un billet ?','a'=>'Créez un compte Spectateur, sélectionnez un concert, choisissez votre type de billet et procédez au paiement via Orange Money ou MTN MoMo. Vous recevrez votre billet QR Code par email et dans votre espace personnel.'],
                ['q'=>'Puis-je annuler mon billet ?','a'=>"L'annulation est possible jusqu'à 48 heures avant la date du concert. Au-delà de ce délai, aucun remboursement ne sera accordé. Consultez nos Conditions de Vente pour plus de détails."],
                ['q'=>'Mon billet est-il nominatif ?','a'=>'Oui, chaque billet est associé à votre compte et comporte un identifiant unique (UUID). Le QR Code est généré au moment de l\'achat et ne peut être dupliqué.'],
            ]],
            ['icon'=>'payments','color'=>'secondary','title'=>'Paiement','faqs'=>[
                ['q'=>'Quels moyens de paiement sont acceptés ?','a'=>'Orange Money et MTN Mobile Money. L\'intégration CinetPay (carte bancaire) est en cours.'],
                ['q'=>'Y a-t-il des frais supplémentaires ?','a'=>'Des frais de service de 2 à 5% peuvent s\'appliquer selon le moyen de paiement choisi. Ces frais sont affichés avant la confirmation.'],
                ['q'=>'Quand vais-je recevoir mon remboursement ?','a'=>'En cas de remboursement validé, le virement est effectué sous 5 à 10 jours ouvrés sur le compte Mobile Money utilisé lors de l\'achat.'],
            ]],
            ['icon'=>'manage_accounts','color'=>'tertiary','title'=>'Compte & Profil','faqs'=>[
                ['q'=>'Quels types de comptes existent ?','a'=>'Spectateur (achat de billets), Organisateur (création de concerts), et Exposant (réservation de stands lors des foires).'],
                ['q'=>'Comment changer mon mot de passe ?','a'=>'Rendez-vous dans votre espace Profil > Sécurité. Saisissez votre mot de passe actuel et choisissez un nouveau.'],
                ['q'=>'Puis-je suivre des artistes ?','a'=>'Oui ! Depuis la fiche d\'un artiste, cliquez sur "Suivre" pour recevoir des notifications lors de ses nouveaux concerts.'],
            ]],
        ];
        @endphp

        @foreach($categories as $cat)
        <div>
            <div class="flex items-center gap-3 mb-5">
                <span class="material-symbols-outlined text-{{ $cat['color'] }}" style="font-variation-settings: 'FILL' 1;">{{ $cat['icon'] }}</span>
                <h2 class="font-headline font-extrabold text-xl uppercase tracking-tight">{{ $cat['title'] }}</h2>
            </div>
            <div class="space-y-3">
                @foreach($cat['faqs'] as $faq)
                <div class="faq-item bg-surface-container-low rounded-2xl border border-outline-variant/10 overflow-hidden"
                     data-q="{{ strtolower($faq['q']).' '.strtolower($faq['a']) }}">
                    <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-5 text-left">
                        <span class="font-headline font-bold text-sm pr-4">{{ $faq['q'] }}</span>
                        <span class="material-symbols-outlined text-{{ $cat['color'] }} faq-icon shrink-0 transition-transform">add</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 text-sm text-on-surface-variant font-body leading-relaxed">{{ $faq['a'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Contact Form -->
<section class="pb-24 px-6 md:px-8 bg-surface-container-low border-t border-outline-variant/10 pt-16">
    <div class="max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <div>
            <span class="inline-block bg-primary/10 text-primary border border-primary/20 px-3 py-1 rounded-full text-xs font-label uppercase tracking-widest font-bold mb-4">Contact Direct</span>
            <h2 class="text-3xl font-headline font-extrabold mb-4 leading-tight">Vous n'avez pas trouvé votre réponse ?</h2>
            <p class="text-on-surface-variant font-body text-sm leading-relaxed mb-8">Notre équipe répond sous <strong class="text-on-surface">24 à 48h ouvrées</strong>. Pour les urgences, précisez-le dans votre message.</p>
            <div class="space-y-4 mb-8">
                <a href="mailto:support@concertcm.com" class="flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-primary">mail</span>
                    </div>
                    <div><p class="font-bold text-sm">support@concertcm.com</p><p class="text-on-surface-variant text-xs">Email principal</p></div>
                </a>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary">schedule</span>
                    </div>
                    <div><p class="font-bold text-sm">Lun–Sam · 8h–20h</p><p class="text-on-surface-variant text-xs">Heure de Douala (WAT)</p></div>
                </div>
            </div>
            <!-- Social links -->
            <p class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-3">Suivez-nous</p>
            <div class="flex items-center gap-3">
                @foreach([
                    ['href'=>'#','label'=>'Facebook','svg'=>'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z','color'=>'primary'],
                    ['href'=>'#','label'=>'Instagram','svg'=>'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z','color'=>'secondary'],
                    ['href'=>'#','label'=>'YouTube','svg'=>'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z','color'=>'primary'],
                ] as $s)
                <a href="{{ $s['href'] }}" class="w-10 h-10 rounded-xl bg-surface-container-highest border border-outline-variant/10 flex items-center justify-center hover:bg-{{ $s['color'] }}/10 hover:border-{{ $s['color'] }}/20 transition-colors group" title="{{ $s['label'] }}">
                    <svg class="w-4 h-4 fill-on-surface-variant group-hover:fill-{{ $s['color'] }} transition-colors" viewBox="0 0 24 24"><path d="{{ $s['svg'] }}"/></svg>
                </a>
                @endforeach
                <a href="https://wa.me/237690000000" class="w-10 h-10 rounded-xl bg-surface-container-highest border border-outline-variant/10 flex items-center justify-center hover:bg-tertiary/10 hover:border-tertiary/20 transition-colors group" title="WhatsApp">
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-tertiary text-sm transition-colors">chat</span>
                </a>
            </div>
        </div>

        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 p-8">
            @if(session('contact_sent'))
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-6xl text-tertiary mb-4 block" style="font-variation-settings: 'FILL' 1;">mark_email_read</span>
                    <h3 class="font-headline font-bold text-xl mb-2">Message envoyé !</h3>
                    <p class="text-on-surface-variant text-sm">Nous vous répondrons dans les 24-48h ouvrées.</p>
                </div>
            @else
                <h3 class="font-headline font-extrabold text-lg mb-6">Envoyer un message</h3>
                <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Votre nom</label>
                        <input name="name" type="text" required value="{{ auth()->user()->name ?? old('name') }}" placeholder="Charlotte Dipanda"
                               class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Email</label>
                        <input name="email" type="email" required value="{{ auth()->user()->email ?? old('email') }}" placeholder="contact@exemple.com"
                               class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Sujet</label>
                        <select name="subject" class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                            <option value="billing">Problème de paiement</option>
                            <option value="ticket">Problème de billet</option>
                            <option value="cancel">Annulation / Remboursement</option>
                            <option value="account">Problème de compte</option>
                            <option value="organizer">Devenir organisateur</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Message</label>
                        <textarea name="message" rows="4" required placeholder="Décrivez votre problème en détail..."
                                  class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full primary-gradient-btn text-on-primary py-3.5 rounded-xl font-headline font-bold transition-transform active:scale-95 flex items-center justify-center gap-2">
                        Envoyer <span class="material-symbols-outlined text-sm">send</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
