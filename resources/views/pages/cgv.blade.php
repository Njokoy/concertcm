@extends('layouts.public')

@section('title', 'Conditions de Vente — ConcertCM+')
@section('description', 'Consultez les conditions générales de vente et d\'utilisation de la plateforme ConcertCM+. Billetterie, remboursements, politique d\'annulation.')

@section('content')

<!-- Hero -->
<section class="relative pt-32 pb-20 px-6 md:px-8 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-secondary/5 blur-[100px] rounded-full"></div>
    </div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <span class="inline-block bg-primary/10 text-primary border border-primary/20 px-4 py-1.5 rounded-full text-xs font-label uppercase tracking-widest font-bold mb-6">Légal</span>
        <h1 class="text-4xl md:text-6xl font-headline font-extrabold tracking-tighter mb-6 leading-tight">
            Conditions<br><span class="text-primary italic">Générales de Vente</span>
        </h1>
        <p class="text-on-surface-variant font-body text-lg max-w-2xl mx-auto">
            Ces conditions régissent l'utilisation de la plateforme ConcertCM+ et l'achat de billets pour les événements musicaux au Cameroun.
        </p>
        <p class="text-on-surface-variant/50 font-label text-xs uppercase tracking-widest mt-6">Dernière mise à jour : Avril 2025 — Version 1.0</p>
    </div>
</section>

<!-- Content -->
<section class="pb-24 px-6 md:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Quick Nav -->
        <div class="bg-surface-container-high border border-outline-variant/10 rounded-3xl p-6 md:p-8 mb-12 sticky top-24 z-10">
            <p class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant mb-4">Sommaire</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs font-label font-bold">
                <a href="#objet" class="text-primary hover:opacity-70 transition-opacity">1. Objet & Parties</a>
                <a href="#service" class="text-on-surface-variant hover:text-primary transition-colors">2. Services</a>
                <a href="#billets" class="text-on-surface-variant hover:text-primary transition-colors">3. Billetterie</a>
                <a href="#paiement" class="text-on-surface-variant hover:text-primary transition-colors">4. Paiement</a>
                <a href="#annulation" class="text-on-surface-variant hover:text-primary transition-colors">5. Annulation</a>
                <a href="#responsabilite" class="text-on-surface-variant hover:text-primary transition-colors">6. Responsabilité</a>
                <a href="#donnees" class="text-on-surface-variant hover:text-primary transition-colors">7. Données</a>
                <a href="#contact" class="text-on-surface-variant hover:text-primary transition-colors">8. Contact</a>
            </div>
        </div>

        <!-- Sections -->
        <div class="space-y-16">

            <!-- Section 1 -->
            <div id="objet" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-headline font-black text-primary text-sm shrink-0">1</div>
                    <h2 class="font-headline font-extrabold text-2xl">Objet & Parties</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed">
                    <p><strong class="text-on-surface">ConcertCM+</strong> est une application web éditée par l'équipe ConcertCM, destinée à centraliser la gestion, la promotion et la billetterie des événements musicaux au Cameroun. La plateforme s'adresse aux <strong class="text-on-surface">Organisateurs</strong>, aux <strong class="text-on-surface">Exposants</strong> et au <strong class="text-on-surface">Grand Public (Spectateurs)</strong> souhaitant découvrir et vivre les événements culturels de leur région.</p>
                    <p>En utilisant la plateforme ou en achetant un billet, vous acceptez sans réserve les présentes Conditions Générales de Vente (CGV). Ces CGV s'appliquent à toute transaction effectuée sur ConcertCM+.</p>
                    <div class="bg-surface-container-high border border-outline-variant/10 rounded-2xl p-6 mt-4">
                        <p class="font-label text-[10px] uppercase tracking-widest text-primary mb-3">Coordonnées de l'éditeur</p>
                        <div class="space-y-1 text-sm">
                            <p><span class="text-on-surface-variant">Plateforme :</span> <strong>ConcertCM+</strong></p>
                            <p><span class="text-on-surface-variant">Email :</span> <strong>contact@concertcm.com</strong></p>
                            <p><span class="text-on-surface-variant">Pays :</span> <strong>Cameroun</strong></p>
                            <p><span class="text-on-surface-variant">Version :</span> <strong>1.0 — Avril 2025</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/10">

            <!-- Section 2 -->
            <div id="service" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center font-headline font-black text-secondary text-sm shrink-0">2</div>
                    <h2 class="font-headline font-extrabold text-2xl">Description des Services</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed">
                    <p>ConcertCM+ propose les services suivants :</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        @foreach([
                            ['icon' => 'confirmation_number', 'color' => 'primary', 'title' => 'Billetterie en ligne', 'desc' => 'Achat sécurisé de billets QR pour concerts et événements'],
                            ['icon' => 'event', 'color' => 'secondary', 'title' => 'Gestion d\'événements', 'desc' => 'Publication et gestion de concerts pour les organisateurs'],
                            ['icon' => 'store', 'color' => 'tertiary', 'title' => 'Réservation de stands', 'desc' => 'Gestion des espaces exposants pour salons et foires'],
                            ['icon' => 'notifications', 'color' => 'primary', 'title' => 'Notifications & Alertes', 'desc' => 'Alertes personnalisées pour les concerts de vos artistes suivis'],
                        ] as $service)
                        <div class="flex gap-4 bg-surface-container-high p-5 rounded-2xl border border-outline-variant/10">
                            <span class="material-symbols-outlined text-{{ $service['color'] }} mt-0.5 shrink-0">{{ $service['icon'] }}</span>
                            <div>
                                <p class="font-headline font-bold text-on-surface text-sm">{{ $service['title'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $service['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/10">

            <!-- Section 3 -->
            <div id="billets" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-tertiary/10 flex items-center justify-center font-headline font-black text-tertiary text-sm shrink-0">3</div>
                    <h2 class="font-headline font-extrabold text-2xl">Achat de Billets</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed">
                    <p>Toute commande de billet vaut acceptation des présentes CGV. La vente est définitive dès la confirmation du paiement. Chaque billet est nominatif, non duplicable, et associé à un QR Code unique.</p>
                    <div class="bg-primary/5 border border-primary/15 rounded-2xl p-6">
                        <p class="font-headline font-bold text-on-surface mb-3 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">info</span> Procédure d'achat</p>
                        <ol class="list-decimal list-inside space-y-2 text-sm">
                            <li>Sélectionnez un concert ou événement disponible sur la plateforme</li>
                            <li>Connectez-vous ou créez un compte Spectateur</li>
                            <li>Complétez le paiement via Orange Money ou MTN Mobile Money</li>
                            <li>Recevez votre billet QR par email et dans votre espace personnel</li>
                            <li>Présentez le QR Code à l'entrée du concert</li>
                        </ol>
                    </div>
                    <p class="text-sm">Les billets électroniques sont valables uniquement pour la date et l'événement spécifiés. Toute tentative de falsification ou de duplication entraîne l'annulation immédiate du billet sans remboursement.</p>
                </div>
            </div>

            <hr class="border-outline-variant/10">

            <!-- Section 4 -->
            <div id="paiement" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-headline font-black text-primary text-sm shrink-0">4</div>
                    <h2 class="font-headline font-extrabold text-2xl">Paiement</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed">
                    <p>Les paiements sont traités de manière sécurisée. Les moyens de paiement acceptés sont adaptés à la réalité du marché camerounais :</p>
                    <div class="flex flex-wrap gap-4 my-4">
                        <div class="flex items-center gap-3 bg-surface-container-high border border-outline-variant/10 rounded-xl px-5 py-3">
                            <span class="material-symbols-outlined text-primary">smartphone</span>
                            <div>
                                <p class="font-bold text-sm">Orange Money</p>
                                <p class="text-[10px] text-on-surface-variant uppercase">Mobile Money</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-surface-container-high border border-outline-variant/10 rounded-xl px-5 py-3">
                            <span class="material-symbols-outlined text-secondary">phone_android</span>
                            <div>
                                <p class="font-bold text-sm">MTN MoMo</p>
                                <p class="text-[10px] text-on-surface-variant uppercase">Mobile Money</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-surface-container-high border border-outline-variant/10 rounded-xl px-5 py-3">
                            <span class="material-symbols-outlined text-tertiary">credit_card</span>
                            <div>
                                <p class="font-bold text-sm">CinetPay</p>
                                <p class="text-[10px] text-on-surface-variant uppercase">Agrégateur (à venir)</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm">Les prix affichés sont en <strong class="text-on-surface">Francs CFA (FCFA)</strong>, toutes taxes comprises. ConcertCM+ se réserve le droit de modifier les tarifs sans préavis pour les futures transactions.</p>
                    <p class="text-sm">Des frais de service de 2–5% peuvent s'appliquer selon le moyen de paiement. Ces frais sont affichés clairement avant la validation de la commande.</p>
                </div>
            </div>

            <hr class="border-outline-variant/10">

            <!-- Section 5 -->
            <div id="annulation" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center font-headline font-black text-error text-sm shrink-0">5</div>
                    <h2 class="font-headline font-extrabold text-2xl">Annulation & Remboursement</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed">
                    <div class="bg-error/5 border border-error/15 rounded-2xl p-6">
                        <p class="font-headline font-bold text-on-surface mb-3 flex items-center gap-2"><span class="material-symbols-outlined text-error text-lg">warning</span> Politique d'annulation</p>
                        <p class="text-sm">L'annulation d'un billet par le spectateur est uniquement possible <strong class="text-on-surface">jusqu'à 48 heures (2 jours) avant la date de l'événement</strong>. Passé ce délai, aucune annulation ni remboursement ne sera accordé.</p>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 text-sm">
                            <span class="material-symbols-outlined text-tertiary mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <p><strong class="text-on-surface">Plus de 48h avant le concert :</strong> Annulation autorisée, remboursement complet sous 5–10 jours ouvrés.</p>
                        </div>
                        <div class="flex items-start gap-3 text-sm">
                            <span class="material-symbols-outlined text-primary mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">info</span>
                            <p><strong class="text-on-surface">Entre 24h et 48h :</strong> Annulation soumise à validation manuelle par l'équipe ConcertCM.</p>
                        </div>
                        <div class="flex items-start gap-3 text-sm">
                            <span class="material-symbols-outlined text-error mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">cancel</span>
                            <p><strong class="text-on-surface">Moins de 24h avant ou après le concert :</strong> Aucun remboursement possible.</p>
                        </div>
                        <div class="flex items-start gap-3 text-sm">
                            <span class="material-symbols-outlined text-secondary mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">event_busy</span>
                            <p><strong class="text-on-surface">Concert annulé par l'organisateur :</strong> Remboursement intégral automatique sous 7 jours.</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/10">

            <!-- Section 6 -->
            <div id="responsabilite" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-headline font-black text-primary text-sm shrink-0">6</div>
                    <h2 class="font-headline font-extrabold text-2xl">Responsabilité</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed text-sm">
                    <p>ConcertCM+ agit en qualité d'intermédiaire technologique entre les organisateurs d'événements et les spectateurs. La plateforme n'est pas responsable :</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>De la qualité artistique ou de l'organisation des concerts listés.</li>
                        <li>Des modifications de programme, d'artistes ou de lieu décidées par l'organisateur.</li>
                        <li>Des incidents survenus lors de l'événement (accidents, vol, etc.).</li>
                        <li>Des problèmes de connectivité empêchant le traitement d'un paiement.</li>
                    </ul>
                    <p>La responsabilité de ConcertCM+ est limitée au montant des billets achetés sur la plateforme.</p>
                </div>
            </div>

            <hr class="border-outline-variant/10">

            <!-- Section 7 -->
            <div id="donnees" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center font-headline font-black text-secondary text-sm shrink-0">7</div>
                    <h2 class="font-headline font-extrabold text-2xl">Protection des Données</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed text-sm">
                    <p>Les données personnelles collectées (nom, email, téléphone, historique d'achat) sont traitées conformément aux lois en vigueur au Cameroun sur la protection des données. Elles sont utilisées exclusivement pour :</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>La fourniture des services de billetterie et de gestion de compte.</li>
                        <li>L'envoi de notifications liées aux concerts et événements.</li>
                        <li>L'amélioration de l'expérience utilisateur sur la plateforme.</li>
                    </ul>
                    <p>Aucune donnée n'est revendue à des tiers sans consentement explicite. Vous disposez d'un droit d'accès, de rectification et de suppression de vos données en contactant <a href="mailto:privacy@concertcm.com" class="text-primary hover:underline">privacy@concertcm.com</a>.</p>
                </div>
            </div>

            <hr class="border-outline-variant/10">

            <!-- Section 8 -->
            <div id="contact" class="scroll-mt-32">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-tertiary/10 flex items-center justify-center font-headline font-black text-tertiary text-sm shrink-0">8</div>
                    <h2 class="font-headline font-extrabold text-2xl">Contact Juridique</h2>
                </div>
                <div class="pl-14 space-y-4 text-on-surface-variant font-body leading-relaxed">
                    <p class="text-sm">Pour toute réclamation ou question relative aux présentes CGV :</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="mailto:legal@concertcm.com" class="flex items-center gap-3 bg-surface-container-high hover:bg-surface-container-highest p-5 rounded-2xl border border-outline-variant/10 transition-colors group">
                            <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">mail</span>
                            <div>
                                <p class="font-bold text-sm text-on-surface">Email légal</p>
                                <p class="text-xs text-on-surface-variant">legal@concertcm.com</p>
                            </div>
                        </a>
                        <a href="{{ route('faq') }}" class="flex items-center gap-3 bg-surface-container-high hover:bg-surface-container-highest p-5 rounded-2xl border border-outline-variant/10 transition-colors group">
                            <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">help</span>
                            <div>
                                <p class="font-bold text-sm text-on-surface">Support & FAQ</p>
                                <p class="text-xs text-on-surface-variant">Nos réponses</p>
                            </div>
                        </a>
                        <div class="flex items-center gap-3 bg-surface-container-high p-5 rounded-2xl border border-outline-variant/10">
                            <span class="material-symbols-outlined text-tertiary">location_on</span>
                            <div>
                                <p class="font-bold text-sm text-on-surface">Adresse</p>
                                <p class="text-xs text-on-surface-variant">Douala, Cameroun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
