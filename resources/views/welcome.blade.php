<x-layouts::public :title="__('Maintenance informatique, réseaux & support — particuliers et entreprises')">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_75%_65%_at_50%_0%,#000_55%,transparent_100%)]"></div>
        <div class="pointer-events-none absolute -top-32 right-8 h-96 w-96 rounded-full bg-flow-500/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/3 h-80 w-80 rounded-full bg-brand-500/40 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-16 px-6 py-24 lg:grid-cols-2 lg:py-32">
            <div class="space-y-8">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-flow-200">
                    <x-icon-bolt class="size-3.5" />
                    Maintenance · Réseaux · Support
                </span>

                <h1 class="text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                    Votre informatique, gérée de <span class="text-flow-400">A à Z</span>.
                </h1>

                <p class="max-w-xl text-lg leading-relaxed text-brand-100/80">
                    {{ config('app.name') }} prend en charge la maintenance de vos équipements, la gestion de vos réseaux et votre support au quotidien — pour les particuliers comme pour les entreprises. Un seul partenaire, un suivi en ligne.
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('contact') }}" class="rounded-full bg-flow-400 px-6 py-3 text-sm font-semibold text-brand-950 shadow-lg shadow-flow-500/25 transition hover:bg-flow-300" wire:navigate>
                        Demander une intervention
                    </a>
                    <a href="{{ route('services') }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10" wire:navigate>
                        Découvrir nos services
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-x-8 gap-y-3 pt-2 text-sm text-brand-100/70">
                    <span class="inline-flex items-center gap-2">
                        <x-icon-clock class="size-4 text-flow-300" />
                        Intervention sous 24 h
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <x-icon-users class="size-4 text-flow-300" />
                        Particuliers &amp; entreprises
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <x-icon-check class="size-4 text-flow-300" />
                        Devis gratuit
                    </span>
                </div>
            </div>

            {{-- Mock espace client --}}
            <div class="relative mx-auto w-full max-w-md">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 items-center justify-center rounded-lg bg-brand-600 ring-1 ring-white/20">
                                <x-app-logo-icon class="size-5 text-white" />
                            </span>
                            <div>
                                <p class="text-sm font-semibold">Ticket #1042</p>
                                <p class="text-xs text-brand-100/60">Ouvert il y a 2 h</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-flow-400/20 px-3 py-1 text-xs font-semibold text-flow-200">En cours</span>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-xl border border-white/10 bg-brand-900/60 p-4">
                            <p class="text-sm font-medium">Imprimante en panne — salle 2</p>
                            <p class="mt-1 text-xs leading-relaxed text-brand-100/60">
                                Bourrage papier récurrent, le technicien intervient avec une pièce de rechange.
                            </p>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-white/10 bg-brand-900/60 p-4 text-xs">
                            <span class="text-brand-100/60">Technicien</span>
                            <span class="font-semibold text-flow-200">Karim · Réseaux</span>
                        </div>
                    </div>

                    <div class="mt-5 space-y-2">
                        <div class="flex items-center justify-between text-xs text-brand-100/60">
                            <span>Résolution</span>
                            <span class="font-semibold text-white">80 %</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full w-4/5 rounded-full bg-flow-400"></div>
                        </div>
                    </div>
                </div>

                <div class="absolute -left-10 top-10 hidden -rotate-3 items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-xs shadow-xl backdrop-blur-sm sm:flex">
                    <x-icon-clock class="size-4 text-flow-300" />
                    <span class="text-brand-100/80">Réponse moyenne <strong class="text-white">1 h 45</strong></span>
                </div>
                <div class="absolute -right-6 bottom-12 hidden rotate-2 items-center gap-2 rounded-xl border border-white/10 bg-brand-600/50 px-4 py-3 text-xs shadow-xl backdrop-blur-sm sm:flex">
                    <x-icon-shield-check class="size-4 text-flow-300" />
                    <span class="text-white"><strong>98&nbsp;%</strong> de résolution</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== SERVICES ===================== --}}
    <section class="bg-zinc-50 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">Nos services</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                    Tout ce que votre informatique demande
                </h2>
                <p class="mt-4 text-lg leading-relaxed text-zinc-600">
                    Un partenaire unique pour dépanner, entretenir, sécuriser et faire évoluer vos équipements et vos réseaux.
                </p>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('services') }}" class="surface surface-hover group p-6" wire:navigate>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-brand-600 text-white shadow-md shadow-brand-600/25">
                        <x-icon-wrench />
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Intervention &amp; maintenance</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Dépannage, entretien préventif et correctif de vos postes, imprimantes et périphériques.
                    </p>
                </a>

                <a href="{{ route('services') }}" class="surface surface-hover group p-6" wire:navigate>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-flow-600 text-white shadow-md shadow-flow-600/25">
                        <x-icon-server />
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Réseaux &amp; connectivité</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Installation, sécurisation et supervision de vos réseaux Wi-Fi et filaires, de votre box à votre pare-feu.
                    </p>
                </a>

                <a href="{{ route('services') }}" class="surface surface-hover group p-6" wire:navigate>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-brand-600 text-white shadow-md shadow-brand-600/25">
                        <x-icon-lifebuoy />
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Support &amp; helpdesk</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Une équipe réactive pour vos incidents et vos questions, avec un suivi de ticket en ligne.
                    </p>
                </a>

                <a href="{{ route('services') }}" class="surface surface-hover group p-6" wire:navigate>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-flow-600 text-white shadow-md shadow-flow-600/25">
                        <x-icon-cube />
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Vente &amp; installation</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Conseil, achat et installation de matériel adapté à vos besoins, pour un tarif maîtrisé.
                    </p>
                </a>

                <a href="{{ route('services') }}" class="surface surface-hover group p-6" wire:navigate>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-brand-600 text-white shadow-md shadow-brand-600/25">
                        <x-icon-cloud />
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Sauvegarde &amp; sécurité</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Sauvegarde automatisée, antivirus et protection de vos données contre la perte et les menaces.
                    </p>
                </a>

                <a href="{{ route('services') }}" class="surface surface-hover group p-6" wire:navigate>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-flow-600 text-white shadow-md shadow-flow-600/25">
                        <x-icon-book-open />
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Accompagnement</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Formation et accompagnement pour adopter les bons outils au quotidien, en toute autonomie.
                    </p>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== POUR QUI ===================== --}}
    <section class="bg-white py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">Pour qui ?</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                    Chez vous comme au bureau
                </h2>
                <p class="mt-4 text-lg leading-relaxed text-zinc-600">
                    Des offres adaptées aux particuliers et aux entreprises, avec le même niveau d'exigence.
                </p>
            </div>

            <div class="mt-14 grid gap-8 lg:grid-cols-2">
                <div class="rounded-3xl border border-brand-100 bg-gradient-to-b from-brand-50 to-white p-8">
                    <span class="flex size-11 items-center justify-center rounded-xl bg-brand-600 text-white shadow-md shadow-brand-600/25">
                        <x-icon-home />
                    </span>
                    <h3 class="mt-5 text-xl font-semibold text-zinc-900">Pour les particuliers</h3>
                    <ul class="mt-5 space-y-3 text-sm text-zinc-700">
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-brand-600" />
                            Dépannage à domicile : PC, imprimante, box, réseaux.
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-brand-600" />
                            Mise en place de votre matériel et du Wi-Fi dans toutes les pièces.
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-brand-600" />
                            Protection de la famille contre les virus et les arnaques en ligne.
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-brand-600" />
                            Tarif horaire clair et devis gratuit avant toute intervention.
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="mt-7 inline-flex rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700" wire:navigate>
                        Faire une demande
                    </a>
                </div>

                <div class="rounded-3xl border border-flow-100 bg-gradient-to-b from-flow-50 to-white p-8">
                    <span class="flex size-11 items-center justify-center rounded-xl bg-flow-600 text-white shadow-md shadow-flow-600/25">
                        <x-icon-building />
                    </span>
                    <h3 class="mt-5 text-xl font-semibold text-zinc-900">Pour les professionnels</h3>
                    <ul class="mt-5 space-y-3 text-sm text-zinc-700">
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-flow-600" />
                            Contrat de maintenance sur mesure pour votre parc informatique.
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-flow-600" />
                            Supervision de vos serveurs, réseaux et équipements critiques.
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-flow-600" />
                            Support prioritaire avec niveau de service (SLA) garanti.
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon-check class="mt-0.5 size-4 shrink-0 text-flow-600" />
                            Un interlocuteur unique et un suivi en ligne de chaque intervention.
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="mt-7 inline-flex rounded-full bg-flow-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-flow-700" wire:navigate>
                        Parler à un expert
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== COMMENT ÇA MARCHE ===================== --}}
    <section class="bg-zinc-50 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">Comment ça marche</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                    Une intervention en 3 étapes
                </h2>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-3">
                <div class="surface p-6">
                    <span class="flex size-10 items-center justify-center rounded-full bg-brand-600 font-bold text-white">1</span>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900">Faites une demande</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Décrivez votre besoin en deux minutes en ligne ou par téléphone, et recevez un devis gratuit.
                    </p>
                </div>
                <div class="surface p-6">
                    <span class="flex size-10 items-center justify-center rounded-full bg-flow-600 font-bold text-white">2</span>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900">Un technicien intervient</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        À distance ou sur place, un technicien qualifié résout le problème avec vos horaires.
                    </p>
                </div>
                <div class="surface p-6">
                    <span class="flex size-10 items-center justify-center rounded-full bg-brand-600 font-bold text-white">3</span>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900">Suivez tout en ligne</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        Dans votre espace client : statut de l'intervention, équipements gérés et historique complet.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA FINAL ===================== --}}
    <section class="relative overflow-hidden bg-brand-950 py-24 text-white">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_50%,#000_60%,transparent_100%)]"></div>
        <div class="pointer-events-none absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-flow-500/25 blur-3xl"></div>

        <div class="relative mx-auto max-w-3xl px-6 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-flow-200">
                <x-icon-bolt class="size-3.5" />
                Devis gratuit &amp; réponse sous 24 h
            </span>
            <h2 class="mt-6 text-3xl font-bold tracking-tight sm:text-4xl">
                Un problème ? On s'en occupe.
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-lg leading-relaxed text-brand-100/80">
                Parlez-nous de votre matériel, de vos réseaux ou de vos projets. On vous rappelle avec une solution et un tarif clair.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="rounded-full bg-flow-400 px-6 py-3 text-sm font-semibold text-brand-950 shadow-lg shadow-flow-500/25 transition hover:bg-flow-300" wire:navigate>
                    Nous contacter
                </a>
                <a href="{{ route('register') }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10" wire:navigate>
                    Ouvrir mon espace client
                </a>
            </div>
        </div>
    </section>

</x-layouts::public>