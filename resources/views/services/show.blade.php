<x-layouts::public :title="$service['name']">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_0%,#000_60%,transparent_100%)]"></div>
        <div class="pointer-events-none absolute -top-24 right-1/4 h-72 w-72 rounded-full {{ $service['tone'] === 'flow' ? 'bg-cyan-500/25' : 'bg-brand-500/30' }} blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 lg:grid-cols-[1.05fr_0.95fr] lg:py-20">
            <div>
                <nav class="flex items-center gap-2 text-sm text-brand-200/60">
                    <a href="{{ route('home') }}" class="transition hover:text-white" wire:navigate>Accueil</a>
                    <span aria-hidden="true">/</span>
                    <a href="{{ route('services') }}" class="transition hover:text-white" wire:navigate>Services</a>
                    <span aria-hidden="true">/</span>
                    <span class="text-brand-100/80">{{ $service['name'] }}</span>
                </nav>

                <span class="mt-7 inline-flex size-12 items-center justify-center rounded-2xl ring-1 ring-white/20 {{ $service['tone'] === 'flow' ? 'bg-cyan-500' : 'bg-brand-600' }}">
                    <x-dynamic-component :component="'icon-'.$service['icon']" class="size-6 text-white" />
                </span>

                <h1 class="mt-5 text-4xl font-bold tracking-tight sm:text-5xl">
                    {{ $service['headline'] }}
                </h1>
                <p class="mt-4 max-w-xl text-lg leading-relaxed text-brand-100/80">
                    {{ $service['description'] }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('contact', ['service' => $service['slug']]) }}" class="rounded-full bg-flow-400 px-6 py-3 text-sm font-semibold text-brand-950 shadow-lg shadow-flow-500/25 transition hover:bg-flow-300" wire:navigate>
                        Demander ce service
                    </a>
                    <a href="{{ route('services') }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10" wire:navigate>
                        Voir tous les services
                    </a>
                </div>
            </div>

            <div class="relative">
                <x-visual-scene :tone="$service['tone']" class="w-full text-white/50" />
                {{-- Pastille du service --}}
                <div class="absolute -bottom-3 left-1/2 flex -translate-x-1/2 items-center gap-3 rounded-2xl border border-white/10 bg-brand-900/90 px-5 py-3.5 shadow-xl backdrop-blur">
                    <span class="flex size-9 items-center justify-center rounded-lg {{ $service['tone'] === 'flow' ? 'bg-cyan-500' : 'bg-brand-600' }}">
                        <x-dynamic-component :component="'icon-'.$service['icon']" class="size-4 text-white" />
                    </span>
                    <span class="text-sm font-semibold text-white">{{ $service['name'] }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Chiffres --}}
    <section class="bg-white py-14">
        <div class="mx-auto grid max-w-7xl gap-6 px-6 sm:grid-cols-3">
            @foreach ($service['metrics'] as $metric)
                <div class="flex items-baseline gap-3 border-l-2 {{ $service['tone'] === 'flow' ? 'border-cyan-400' : 'border-brand-500' }} pl-4">
                    <span class="text-3xl font-bold tracking-tight text-zinc-900">{{ $metric['value'] }}</span>
                    <span class="text-sm text-zinc-500">{{ $metric['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Ce qui est inclus --}}
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-[0.9fr_1.1fr]">
            <div>
                <p class="font-mono text-xs uppercase tracking-widest {{ $service['tone'] === 'flow' ? 'text-cyan-600' : 'text-brand-600' }}">Périmètre</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Ce qui est inclus</h2>
                <p class="mt-3 leading-relaxed text-zinc-600">
                    Chaque intervention est facturée au juste prix, en ponctuel ou dans le cadre d'un
                    contrat. Pas d'abonnement imposé, pas de surprise en fin de mois : vous ne payez
                    que ce dont vous avez réellement besoin.
                </p>
                <p class="mt-6 text-sm font-medium text-zinc-800">
                    Vous n'êtes pas sûr de votre besoin ? Parlez-nous simplement de votre situation.
                </p>
            </div>

            <ul class="grid content-start gap-4 sm:grid-cols-2">
                @foreach ($service['features'] as $feature)
                    <li class="flex items-start gap-3 rounded-2xl border border-zinc-200/70 bg-white p-4">
                        <span class="{{ $service['tone'] === 'flow' ? 'text-cyan-500' : 'text-brand-600' }}">
                            <x-icon-check class="mt-0.5 size-5" />
                        </span>
                        <span class="text-sm leading-relaxed text-zinc-700">{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Déroulement --}}
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="max-w-2xl">
                <p class="font-mono text-xs uppercase tracking-widest {{ $service['tone'] === 'flow' ? 'text-cyan-600' : 'text-brand-600' }}">Déroulement</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Comment on procède</h2>
            </div>

            @php
                $steps = [
                    ['title' => 'Soumission en ligne', 'text' => 'Décrivez votre besoin via le formulaire de contact. Précis, ça va plus vite.'],
                    ['title' => 'Validation & planification', 'text' => 'Nous revenons vers vous sous 24 h pour cadrer, chiffrer et planifier l\'intervention. Vous validez, on démarre.'],
                    ['title' => 'Intervention & suivi', 'text' => 'Le technicien intervient, puis votre dossier est suivi dans votre espace client, avec historique complet.'],
                ];
            @endphp

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ($steps as $i => $step)
                    <div class="relative rounded-3xl border border-zinc-200/70 bg-zinc-50 p-6">
                        <span class="flex size-10 items-center justify-center rounded-full font-mono text-sm font-bold {{ $service['tone'] === 'flow' ? 'bg-cyan-500 text-white' : 'bg-brand-600 text-white' }}">0{{ $i + 1 }}</span>
                        <h3 class="mt-4 font-semibold text-zinc-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Autres services --}}
    <section class="bg-zinc-50 py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-zinc-900">Nos autres services</h2>
                <a href="{{ route('services') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 transition hover:gap-2.5" wire:navigate>
                    Tout voir <span aria-hidden="true">→</span>
                </a>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                @foreach (collect($services)->where('slug', '!=', $service['slug']) as $other)
                    <a href="{{ route('services.show', $other['slug']) }}" class="flex items-center gap-2.5 rounded-full border border-zinc-200/80 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-brand-300 hover:text-brand-700" wire:navigate>
                        <span class="{{ $other['tone'] === 'flow' ? 'text-cyan-500' : 'text-brand-600' }}">
                            <x-dynamic-component :component="'icon-'.$other['icon']" class="size-4" />
                        </span>
                        {{ $other['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <x-public-cta
        title="Besoin sur ce sujet ?"
        :subtitle="'Parlons-en : un devis gratuit, une réponse sous 24 h, et un suivi en ligne dès la validation.'"
    />

</x-layouts::public>