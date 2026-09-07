<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-32 -right-24 h-96 w-96 rounded-full bg-brand-500/15 blur-3xl dark:bg-brand-500/20"></div>
            <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-flow-400/15 blur-3xl dark:bg-flow-400/20"></div>
        </div>

        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" wire:navigate>
                <span class="flex size-10 items-center justify-center rounded-lg bg-brand-600 shadow-md shadow-brand-600/25 ring-1 ring-white/10">
                    <x-app-logo-icon class="size-5 text-white" />
                </span>
                <span class="text-xl font-bold tracking-tight">{{ config('app.name') }}</span>
            </a>

            <div class="flex w-full max-w-sm flex-col gap-2">
                <div class="surface p-8">
                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
                <p class="text-center text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('Vos systèmes, entre de bonnes mains.') }}
                </p>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>