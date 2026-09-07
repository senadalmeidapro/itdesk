<x-layouts::public :title="__('Nos services')">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_0%,#000_60%,transparent_100%)]"></div>
        <div class="pointer-events-none absolute -top-24 right-16 h-72 w-72 rounded-full bg-flow-500/25 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl items-end gap-8 px-6 py-16 lg:grid-cols-[1fr_360px] lg:py-20">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-flow-200">
                    <x-icon-bolt class="size-3.5" />
                    Un partenaire unique, du diagnostic à la formation
                </span>
                <h1 class="mt-5 text-4xl font-bold tracking-tight sm:text-5xl">
                    Nos <span class="text-flow-400">services</span>
                </h1>
                <p class="mt-4 max-w-2xl text-lg leading-relaxed text-brand-100/80">
                    De l'intervention ponctuelle au contrat de maintenance complet, nous couvrons
                    l'ensemble du cycle de vie de vos équipements, de vos réseaux et de vos données.
                    Choisissez votre besoin, nous nous occupons du reste.
                </p>
            </div>

            <a href="{{ route('contact') }}" class="group hidden items-center gap-4 rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur transition hover:border-white/25 hover:bg-white/10 lg:flex" wire:navigate>
                <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-flow-400 text-brand-950">
                    <x-icon-paperclip class="size-5" />
                </span>
                <span>
                    <span class="block text-sm font-semibold text-white">Un besoin précis ?</span>
                    <span class="block text-sm text-brand-200/70">Décrivez-le, réponse sous 24 h →</span>
                </span>
            </a>
        </div>
    </section>

    {{-- Grille de services --}}
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <a
                        href="{{ route('services.show', $service['slug']) }}"
                        class="group flex flex-col overflow-hidden rounded-3xl border border-zinc-200/70 bg-white shadow-sm transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl"
                        wire:navigate
                    >
                        <div class="relative h-44 overflow-hidden bg-brand-950 text-white/40">
                            <x-dynamic-component :component="'scene-'.$service['illustration']" :tone="$service['tone']" class="absolute inset-0 size-full text-white/60 transition duration-500 group-hover:scale-105" />
                            <span class="absolute left-4 top-4 flex size-10 items-center justify-center rounded-xl ring-1 ring-white/20 {{ $service['tone'] === 'flow' ? 'bg-cyan-500/90' : 'bg-brand-600' }}">
                                <x-dynamic-component :component="'icon-'.$service['icon']" class="size-5 text-white" />
                            </span>
                        </div>

                        <div class="flex flex-1 flex-col p-6">
                            <h2 class="text-lg font-semibold text-zinc-900">{{ $service['name'] }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $service['short'] }}</p>

                            <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 transition group-hover:gap-2.5">
                                Découvrir ce service
                                <span aria-hidden="true">→</span>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Comment ça marche --}}
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="max-w-2xl">
                <p class="font-mono text-xs uppercase tracking-widest text-brand-600">Simple, tracé, suivi</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Un parcours en trois temps</h2>
                <p class="mt-3 text-zinc-600">Peu importe le service, votre demande suit toujours le même chemin — et vous gardez la main.</p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @php
                    $steps = [
                        ['icon' => 'paperclip', 'tone' => 'brand', 'num' => '01', 'title' => 'Décrivez votre besoin', 'text' => 'Un formulaire simple, une réponse humaine. Nous revenons vers vous sous 24 h pour cadrer la demande.'],
                        ['icon' => 'magnifying-glass', 'tone' => 'flow', 'num' => '02', 'title' => 'Nous validons ensemble', 'text' => 'Diagnostic partagé, proposition claire et devis gratuit. Vous validez, le dossier devient une intervention suivie.'],
                        ['icon' => 'check', 'tone' => 'brand', 'num' => '03', 'title' => 'Suivi en ligne jusqu’au bout', 'text' => 'Une fois en cours, votre dossier est suivi dans votre espace client : statut, technicien et historique à tout moment.'],
                    ];
                @endphp

                @foreach ($steps as $i => $step)
                    <div class="relative rounded-3xl border border-zinc-200/70 bg-zinc-50 p-6">
                        <p class="font-mono text-xs text-zinc-400">0{{ $i + 1 }}</p>
                        <span class="mt-4 flex size-11 items-center justify-center rounded-xl text-white {{ $step['tone'] === 'flow' ? 'bg-cyan-500' : 'bg-brand-600' }}">
                            <x-dynamic-component :component="'icon-'.$step['icon']" class="size-5" />
                        </span>
                        <h3 class="mt-4 font-semibold text-zinc-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <x-public-cta
        title="Un projet ou une panne ?"
        subtitle="Décrivez votre besoin en ligne : nous vous recontactons sous 24 h et lançons l'intervention en temps voulu."
    />

</x-layouts::public>