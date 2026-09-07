<x-layouts::public :title="__('À propos')">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_0%,#000_60%,transparent_100%)]"></div>
        <div class="pointer-events-none absolute -top-24 left-1/3 h-72 w-72 rounded-full bg-flow-500/25 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 lg:grid-cols-[1.1fr_0.9fr] lg:py-20">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-flow-200">
                    <x-icon-bolt class="size-3.5" />
                    La maison de confiance du numérique
                </span>
                <h1 class="mt-5 text-4xl font-bold tracking-tight sm:text-5xl">
                    Vos systèmes, entre <span class="text-flow-400">de bonnes mains.</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-relaxed text-brand-100/80">
                    TAKTIC accompagne les particuliers et les entreprises au quotidien : maintenance,
                    réseaux, sauvegarde, support. Notre promesse : du concret, de la réactivité,
                    et une traçabilité totale sur chaque intervention.
                </p>
            </div>

            <x-visual-scene tone="flow" class="w-full text-white/50" />
        </div>
    </section>

    {{-- Chiffres clés --}}
    <section class="bg-white py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 text-center sm:grid-cols-2 lg:grid-cols-4">
            @php
                $stats = [
                    ['value' => '24 h', 'label' => 'de temps de réponse à une demande'],
                    ['value' => '100 %', 'label' => 'des interventions tracées en ligne'],
                    ['value' => '3-2-1', 'label' => 'règle de sauvegarde appliquée'],
                    ['value' => '6 j/7', 'label' => 'de disponibilité du support'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="rounded-3xl border border-zinc-200/70 bg-zinc-50 p-6">
                    <p class="text-3xl font-bold tracking-tight text-brand-700">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-sm text-zinc-500">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Qui sommes-nous --}}
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 lg:grid-cols-2">
            <div class="relative">
                <div class="rounded-3xl bg-brand-950 p-8 text-white">
                    <span class="flex size-12 items-center justify-center rounded-2xl bg-brand-600 ring-1 ring-white/20">
                        <x-app-logo-icon class="size-6 text-white" />
                    </span>
                    <p class="mt-6 text-lg font-semibold leading-relaxed text-flow-200">
                        « Un ordinateur, c'est un outil. Notre métier, c'est qu'il serve votre quotidien — pas qu'il vous en empêche. »
                    </p>
                    <p class="mt-4 font-mono text-xs uppercase tracking-widest text-brand-200/50">L'équipe TAKTIC</p>
                </div>
                <div class="absolute -bottom-5 -right-5 -z-10 hidden h-40 w-40 rounded-3xl bg-flow-300/40 blur-2xl sm:block"></div>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-widest text-brand-600">Qui nous sommes</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Une équipe qui parle simplement, des solutions qui durent</h2>
                <p class="mt-4 leading-relaxed text-zinc-600">
                    Nous avons fait le choix d'une approche claire : que vous soyez une famille avec un
                    poste unique ou une entreprise avec un parc complet, vous méritez la même exigence —
                    et la même clarté. Pas de jargon inutile, pas de services vendus à tout prix.
                </p>
                <p class="mt-4 leading-relaxed text-zinc-600">
                    Chaque demande, intégrée en ligne ou passée au comptoir, devient un dossier suivi.
                    Vous savez toujours qui intervient, quand, et ce qui a été fait.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('services') }}" class="rounded-full bg-brand-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" wire:navigate>
                        Découvrir nos services
                    </a>
                    <a href="{{ route('contact') }}" class="rounded-full border border-zinc-300 px-6 py-3 text-sm font-semibold text-zinc-800 transition hover:border-brand-400 hover:text-brand-700" wire:navigate>
                        Nous écrire
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Nos valeurs --}}
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="max-w-2xl">
                <p class="font-mono text-xs uppercase tracking-widest text-brand-600">Nos valeurs</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Trois principes, zéro compromis</h2>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @php
                    $values = [
                        ['icon' => 'bolt', 'title' => 'Réactivité', 'text' => 'Une demande bien cadrée reçoit une réponse sous 24 h. Le temps, c\'est votre activité qui tourne.'],
                        ['icon' => 'shield-check', 'title' => 'Confiance', 'text' => 'Données protégées, matériel propre, interventions tracées. Vous savez tout ce que nous faisons.'],
                        ['icon' => 'book-open', 'title' => 'Transmission', 'text' => 'On répare, on sécurise — et on vous forme pour que vous repreniez la main en autonomie.'],
                    ];
                @endphp
                @foreach ($values as $value)
                    <div class="rounded-3xl border border-zinc-200/70 bg-zinc-50 p-7">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-brand-600 text-white">
                            <x-dynamic-component :component="'icon-'.$value['icon']" class="size-5" />
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-zinc-900">{{ $value['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $value['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <x-public-cta
        title="Envie d'en parler ?"
        subtitle="Une question, un doute, un projet : écrivez-nous en ligne, on vous répond humainement."
    />

</x-layouts::public>