<x-layouts::public :title="__('Nos services')">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_0%,#000_60%,transparent_100%)]"></div>
        <div class="pointer-events-none absolute -top-24 right-16 h-72 w-72 rounded-full bg-flow-500/25 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-6 py-16 lg:py-20">
            <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">
                Nos <span class="text-flow-400">services</span>
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-brand-100/80">
                De l'intervention ponctuelle au contrat de maintenance complet, nous couvrons l'ensemble du cycle de vie de vos équipements et de vos réseaux.
            </p>
        </div>
    </section>

    {{-- Détais --}}
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl space-y-6 px-6">
            @php
                $services = [
                    [
                        'icon' => 'wrench',
                        'title' => 'Intervention & maintenance',
                        'description' => 'Dépannage et entretien de vos postes de travail, imprimantes et périphériques, en préventif comme en correctif.',
                        'items' => ['Diagnostic et dépannage matériel et logiciel', 'Entretien préventif de votre parc', 'Remplacement et nettoyage de composants', 'Mise à jour des systèmes et applications'],
                    ],
                    [
                        'icon' => 'server',
                        'title' => 'Réseaux & connectivité',
                        'description' => 'Conception, installation et supervision de votre réseau pour une connexion stable et sécurisée, chez vous comme au bureau.',
                        'items' => ['Installation et optimisation de la box et du Wi-Fi', 'Câblage et raccordement de vos locaux', 'Pare-feu et sécurisation du réseau', 'Supervision et alerte en cas de panne'],
                    ],
                    [
                        'icon' => 'lifebuoy',
                        'title' => 'Support & helpdesk',
                        'description' => 'Une équipe réactive pour répondre à vos incidents et vos questions, simplement, avec un suivi de demande en ligne.',
                        'items' => ['Assistance à distance et sur site', 'Suivi de ticket dans votre espace client', 'Accompagnement sur vos outils', 'Contrat de support avec niveau de service (SLA)'],
                    ],
                    [
                        'icon' => 'cube',
                        'title' => 'Vente & installation',
                        'description' => 'Un conseil neutre pour choisir le bon matériel, au bon prix, installé et configuré par nos soins.',
                        'items' => ['Conseil et devis matériel personnalisé', 'Ordinateurs, serveurs et périphériques', 'Installation et configuration complètes', 'Reprise et recyclage de l\'ancien matériel'],
                    ],
                    [
                        'icon' => 'cloud',
                        'title' => 'Sauvegarde & sécurité',
                        'description' => 'Protégez vos données et votre activité contre la perte, les pannes et les menaces en ligne.',
                        'items' => ['Sauvegarde automatisée, locale et cloud', 'Antivirus et protection des postes', 'Mise à jour de sécurité suivie', 'Plan de reprise après incident'],
                    ],
                    [
                        'icon' => 'book-open',
                        'title' => 'Accompagnement & formation',
                        'description' => 'Montez en compétences et gagnez en autonomie avec des sessions pratiques adaptées à votre niveau.',
                        'items' => ['Formation individuelle ou en petit groupe', 'Prise en main de vos outils métier', 'Bonnes pratiques de sécurité au quotidien', 'Support documentaire et tutoriels'],
                    ],
                ];
            @endphp

            @foreach ($services as $i => $service)
                <div class="surface surface-hover overflow-hidden">
                    <div class="grid gap-0 md:grid-cols-[240px_1fr]">
                        <div class="flex items-start gap-4 bg-brand-950 p-6 text-white md:flex-col md:gap-0">
                            <span class="flex size-11 items-center justify-center rounded-xl bg-brand-600 ring-1 ring-white/20">
                                <x-dynamic-component :component="'icon-'.$service['icon']" />
                            </span>
                            <div class="mt-0 md:mt-4">
                                <p class="font-mono text-xs text-flow-300">0{{ $i + 1 }}</p>
                            </div>
                        </div>
                        <div class="p-6 md:p-8">
                            <h2 class="text-xl font-semibold text-zinc-900">{{ $service['title'] }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $service['description'] }}</p>
                            <ul class="mt-5 grid gap-2.5 sm:grid-cols-2">
                                @foreach ($service['items'] as $item)
                                    <li class="flex items-start gap-2.5 text-sm text-zinc-700">
                                        <x-icon-check class="mt-0.5 size-4 shrink-0 text-brand-600" />
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('contact') }}" class="mt-6 inline-flex text-sm font-semibold text-brand-600 transition hover:text-brand-700" wire:navigate>
                                Demander un devis gratuit
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-brand-950 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-6 text-center lg:flex-row lg:text-start">
            <div>
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Un projet ou une panne ?</h2>
                <p class="mt-2 text-brand-100/80">Un devis gratuit, une réponse sous 24 h.</p>
            </div>
            <a href="{{ route('contact') }}" class="shrink-0 rounded-full bg-flow-400 px-6 py-3 text-sm font-semibold text-brand-950 shadow-lg shadow-flow-500/25 transition hover:bg-flow-300" wire:navigate>
                Nous contacter
            </a>
        </div>
    </section>

</x-layouts::public>