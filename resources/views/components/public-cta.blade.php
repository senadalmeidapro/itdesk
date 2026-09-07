<section class="relative overflow-hidden bg-brand-950 py-16 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_50%,#000_60%,transparent_100%)]"></div>
    <div class="pointer-events-none absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-flow-500/25 blur-3xl"></div>

    <div class="relative mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-6 text-center lg:flex-row lg:text-start">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-flow-200">
                <x-icon-bolt class="size-3.5" />
                {{ $eyebrow ?? 'Devis gratuit & réponse sous 24 h' }}
            </span>
            <h2 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl">{{ $title }}</h2>
            <p class="mt-2 max-w-xl text-brand-100/80">{{ $subtitle }}</p>
        </div>

        <div class="flex shrink-0 flex-wrap items-center justify-center gap-3">
            <a href="{{ route('contact') }}" class="rounded-full bg-flow-400 px-6 py-3 text-sm font-semibold text-brand-950 shadow-lg shadow-flow-500/25 transition hover:bg-flow-300" wire:navigate>
                {{ $primaryLabel ?? 'Nous contacter' }}
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10" wire:navigate>
                    Mon espace client
                </a>
            @else
                <a href="{{ route('register') }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10" wire:navigate>
                    Ouvrir mon espace client
                </a>
            @endauth
        </div>
    </div>
</section>