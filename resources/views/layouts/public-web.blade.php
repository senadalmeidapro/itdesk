<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased">
        <header class="sticky top-0 z-40 border-b border-white/10 bg-brand-950/80 backdrop-blur-md">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5" wire:navigate>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-brand-600 ring-1 ring-white/20">
                        <x-app-logo-icon class="size-5 text-white" />
                    </span>
                    <span class="text-lg font-bold tracking-tight text-white">{{ config('app.name') }}</span>
                </a>

                <nav class="hidden items-center gap-7 md:flex">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-white' : 'text-brand-100/80' }} text-sm font-medium transition hover:text-white" wire:navigate>{{ __('Accueil') }}</a>
                    <a href="{{ route('services') }}" class="{{ request()->routeIs('services*') ? 'text-white' : 'text-brand-100/80' }} text-sm font-medium transition hover:text-white" wire:navigate>{{ __('Services') }}</a>
                    <a href="{{ route('a-propos') }}" class="{{ request()->routeIs('a-propos') ? 'text-white' : 'text-brand-100/80' }} text-sm font-medium transition hover:text-white" wire:navigate>{{ __('À propos') }}</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact*') ? 'text-white' : 'text-brand-100/80' }} text-sm font-medium transition hover:text-white" wire:navigate>{{ __('Contact') }}</a>
                </nav>

                <div class="flex items-center gap-3">
                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-brand-800 transition hover:bg-flow-200"
                        >
                            {{ __('Mon espace') }}
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="hidden text-sm font-medium text-brand-100/80 transition hover:text-white md:block"
                            wire:navigate
                        >
                            {{ __('Se connecter') }}
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-brand-800 transition hover:bg-flow-200"
                            wire:navigate
                        >
                            {{ __('Espace client') }}
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="bg-brand-950 text-brand-100">
            <div class="mx-auto grid max-w-7xl gap-10 px-6 py-16 md:grid-cols-2 lg:grid-cols-4">
                <div class="space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <span class="flex size-9 items-center justify-center rounded-lg bg-brand-600 ring-1 ring-white/20">
                            <x-app-logo-icon class="size-5 text-white" />
                        </span>
                        <span class="text-lg font-bold tracking-tight text-white">{{ config('app.name') }}</span>
                    </a>
                    <p class="max-w-xs text-sm leading-relaxed text-brand-200/70">
                        Maintenance informatique, réseaux et support pour les particuliers et les entreprises.
                        Un partenaire unique, un suivi en ligne.
                    </p>
                </div>

                <div class="space-y-3 text-sm">
                    <p class="font-semibold text-white">{{ __('Services') }}</p>
                    <ul class="space-y-2 text-brand-200/70">
                        @foreach (config('public-services.services') as $publicService)
                            <li>
                                <a href="{{ route('services.show', $publicService['slug']) }}" class="transition hover:text-white" wire:navigate>{{ $publicService['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="space-y-3 text-sm">
                    <p class="font-semibold text-white">{{ __('Liens') }}</p>
                    <ul class="space-y-2 text-brand-200/70">
                        <li>
                            <a href="{{ route('a-propos') }}" class="transition hover:text-white" wire:navigate>{{ __('À propos') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('services') }}" class="transition hover:text-white" wire:navigate>{{ __('Tous nos services') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="transition hover:text-white" wire:navigate>{{ __('Demande d\'intervention') }}</a>
                        </li>
                        <li>
                            @auth
                                <a href="{{ route('dashboard') }}" class="transition hover:text-white" wire:navigate>{{ __('Mon espace client') }}</a>
                            @else
                                <a href="{{ route('register') }}" class="transition hover:text-white" wire:navigate>{{ __('Créer un compte') }}</a>
                            @endauth
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10">
                <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-6 py-6 text-xs text-brand-200/50 sm:flex-row">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
                    <p>{{ __('Vos systèmes, entre de bonnes mains.') }}</p>
                </div>
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>