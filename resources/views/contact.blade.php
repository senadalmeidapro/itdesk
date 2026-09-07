<x-layouts::public :title="__('Contact & demande d\'intervention')">

    <section class="bg-white py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-brand-600">Contact</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                    Décrivez-nous votre besoin
                </h1>
                <p class="mt-4 text-lg leading-relaxed text-zinc-600">
                    Devis gratuit et réponse sous 24 h. Un technicien vous rappelle avec une solution et un tarif clair.
                </p>
            </div>

            <div class="mx-auto mt-14 grid max-w-5xl gap-8 lg:grid-cols-5">
                {{-- Infos --}}
                <div class="space-y-5 lg:col-span-2">
                    <div class="rounded-3xl bg-brand-950 p-8 text-white">
                        <p class="text-sm font-semibold text-flow-300">Une question ?</p>
                        <h2 class="mt-2 text-2xl font-bold tracking-tight">Parlez à un expert</h2>
                        <p class="mt-3 text-sm leading-relaxed text-brand-100/70">
                            Notre équipe vous conseille, qu'il s'agisse d'un dépannage urgent, d'un projet de réseau ou d'un contrat de maintenance.
                        </p>

                        <ul class="mt-8 space-y-4 text-sm">
                            <li class="flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-lg bg-white/10">
                                    <x-icon-lifebuoy class="size-4 text-flow-300" />
                                </span>
                                <span class="text-brand-100/80">{{ config('app.name') }} — support</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-lg bg-white/10">
                                    <x-icon-clock class="size-4 text-flow-300" />
                                </span>
                                <span class="text-brand-100/80">Réponse sous 24 h, 6 j/7</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-lg bg-white/10">
                                    <x-icon-check class="size-4 text-flow-300" />
                                </span>
                                <span class="text-brand-100/80">Intervention à distance ou sur place</span>
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-flow-100 bg-flow-50 p-6">
                        <p class="flex items-center gap-2 text-sm font-semibold text-flow-800">
                            <x-icon-bolt class="size-4" />
                            Déjà client ?
                        </p>
                        <p class="mt-2 text-sm leading-relaxed text-flow-900/70">
                            Ouvrez un ticket directement depuis votre espace client pour un suivi en ligne complet.
                        </p>
                        <a href="{{ route('login') }}" class="mt-4 inline-flex rounded-full bg-flow-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-flow-700" wire:navigate>
                            Accéder à mon espace
                        </a>
                    </div>
                </div>

                {{-- Formulaire --}}
                <div class="surface p-8 lg:col-span-3">
                    @if (session('status'))
                        <div class="mb-6 flex items-start gap-3 rounded-xl border border-flow-200 bg-flow-50 p-4 text-sm text-flow-800">
                            <x-icon-check class="mt-0.5 size-5 shrink-0" />
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <span class="mb-3 block text-sm font-medium text-zinc-700">{{ __('Vous êtes ?') }}</span>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-zinc-200 p-4 text-sm transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:ring-1 has-[:checked]:ring-brand-500">
                                    <input type="radio" name="audience" value="particulier" class="size-4 accent-brand-600" @checked(old('audience', 'particulier') === 'particulier') />
                                    <span class="flex items-center gap-2">
                                        <x-icon-home class="size-4 text-brand-600" />
                                        <span class="font-medium text-zinc-900">Particulier</span>
                                    </span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-zinc-200 p-4 text-sm transition has-[:checked]:border-flow-500 has-[:checked]:bg-flow-50 has-[:checked]:ring-1 has-[:checked]:ring-flow-500">
                                    <input type="radio" name="audience" value="entreprise" class="size-4 accent-flow-600" @checked(old('audience') === 'entreprise') />
                                    <span class="flex items-center gap-2">
                                        <x-icon-building class="size-4 text-flow-600" />
                                        <span class="font-medium text-zinc-900">Entreprise</span>
                                    </span>
                                </label>
                            </div>
                            @error('audience')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Nom complet') }}</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" class="w-full rounded-lg border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm placeholder-zinc-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30" />
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Téléphone') }} <span class="font-normal text-zinc-400">(optionnel)</span></label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="w-full rounded-lg border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm placeholder-zinc-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30" />
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Adresse email') }}</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-lg border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm placeholder-zinc-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30" />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Sujet') }}</label>
                            <select id="subject" name="subject" required class="w-full rounded-lg border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                                <option value="Dépannage matériel" @selected(old('subject') === 'Dépannage matériel')>Dépannage matériel</option>
                                <option value="Problème de réseau / Wi-Fi" @selected(old('subject') === 'Problème de réseau / Wi-Fi')>Problème de réseau / Wi-Fi</option>
                                <option value="Installation & mise en place" @selected(old('subject') === 'Installation & mise en place')>Installation &amp; mise en place</option>
                                <option value="Contrat de maintenance" @selected(old('subject') === 'Contrat de maintenance')>Contrat de maintenance</option>
                                <option value="Sécurité & sauvegarde" @selected(old('subject') === 'Sécurité & sauvegarde')>Sécurité &amp; sauvegarde</option>
                                <option value="Autre demande" @selected(old('subject') === 'Autre demande')>Autre demande</option>
                            </select>
                            @error('subject')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Décrivez votre besoin') }}</label>
                            <textarea id="message" name="message" rows="5" required class="w-full rounded-lg border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm placeholder-zinc-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <p class="text-xs text-zinc-500">
                                Réponse garantie sous 24 h ouvrées. Vos données restent confidentielles.
                            </p>
                            <flux:button variant="primary" type="submit" class="!rounded-full shrink-0">
                                {{ __('Envoyer ma demande') }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</x-layouts::public>