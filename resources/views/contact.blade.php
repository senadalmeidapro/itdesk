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

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6" x-data="{ service: serviceSlugInitial, subject: subjectInitial, subjectBy: subjectByService }">
                        @csrf

                        @php
                            $services = config('public-services.services');
                            $fromService = collect($services)->firstWhere('slug', request('service'));
                            $subjectBySlug = [
                                'maintenance-depannage' => 'Dépannage matériel',
                                'reseaux-connectivite' => 'Problème de réseau / Wi-Fi',
                                'vente-installation' => 'Installation & mise en place',
                                'sauvegarde-securite' => 'Sauvegarde & sécurité',
                                'support-helpdesk' => 'Support & accompagnement',
                                'accompagnement-formation' => 'Support & accompagnement',
                            ];
                            $subjectByService = collect($services)->mapWithKeys(fn (array $svc): array => [
                                $svc['slug'] => $subjectBySlug[$svc['slug']] ?? 'Autre demande',
                            ])->all();
                            $serviceSlug = (string) old('service_slug', $fromService['slug'] ?? '');
                            $subjectInitial = (string) old('subject', $fromService ? ($subjectBySlug[$fromService['slug']] ?? 'Autre demande') : 'Autre demande');
                        @endphp

                        <script>
                            const serviceSlugInitial = @js($serviceSlug);
                            const subjectInitial = @js($subjectInitial);
                            const subjectByService = @js($subjectByService);
                        </script>

                        {{-- Service concerné --}}
                        <div>
                            <label for="service_slug" class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Service concerné') }}</label>
                            <select id="service_slug" name="service_slug" x-model="service" @change="subject = subjectBy[service] ?? 'Autre demande'" class="w-full rounded-lg border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                                <option value="">Je ne sais pas encore / autre demande</option>
                                @foreach ($services as $svc)
                                    <option value="{{ $svc['slug'] }}">{{ $svc['name'] }}</option>
                                @endforeach
                            </select>
                            @error('service_slug')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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
                            <select id="subject" name="subject" required x-model="subject" class="w-full rounded-lg border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                                <option value="Dépannage matériel">Dépannage matériel</option>
                                <option value="Problème de réseau / Wi-Fi">Problème de réseau / Wi-Fi</option>
                                <option value="Installation & mise en place">Installation &amp; mise en place</option>
                                <option value="Contrat de maintenance">Contrat de maintenance</option>
                                <option value="Sauvegarde & sécurité">Sauvegarde &amp; sécurité</option>
                                <option value="Support & accompagnement">Support &amp; accompagnement</option>
                                <option value="Autre demande">Autre demande</option>
                            </select>
                            @error('subject')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Champs personnalisés selon le service sélectionné --}}
                        @foreach ($services as $svc)
                            @php $fields = config('service-form-fields.'.$svc['slug'], []); @endphp
                            @if ($fields)
                                <fieldset
                                    x-show="service === '{{ $svc['slug'] }}'"
                                    x-cloak
                                    :disabled="service !== '{{ $svc['slug'] }}'"
                                    class="space-y-4 rounded-2xl border border-flow-100 bg-flow-50/50 p-5"
                                >
                                    <legend class="px-2 text-sm font-semibold text-flow-800">
                                        Précisions — {{ $svc['name'] }}
                                    </legend>
                                    <div class="grid gap-5 sm:grid-cols-2">
                                        @foreach ($fields as $field)
                                            @php
                                                $full = ($field['column'] ?? 'half') === 'full';
                                                $required = (bool) ($field['required'] ?? false);
                                                $errorKey = 'form_data.'.$field['name'];
                                            @endphp
                                            <div @class(['sm:col-span-2' => $full, 'sm:col-span-1' => ! $full])>
                                                <label for="form_data_{{ $field['name'] }}" class="mb-1.5 block text-sm font-medium text-zinc-700">
                                                    {{ $field['label'] }}
                                                    @if (! $required)
                                                        <span class="font-normal text-zinc-400">(optionnel)</span>
                                                    @endif
                                                </label>

                                                @switch($field['type'])
                                                    @case('select')
                                                        <select id="form_data_{{ $field['name'] }}" name="form_data[{{ $field['name'] }}]" @required($required) :required="service === '{{ $svc['slug'] }}'" class="w-full rounded-lg border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                                                            <option value="" hidden></option>
                                                            @foreach ($field['options'] as $optionValue => $optionLabel)
                                                                <option value="{{ $optionValue }}" @selected(old('form_data.'.$field['name']) === $optionValue)>{{ $optionLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                        @break

                                                    @case('textarea')
                                                        <textarea id="form_data_{{ $field['name'] }}" name="form_data[{{ $field['name'] }}]" rows="3" placeholder="{{ $field['placeholder'] ?? '' }}" @required($required) :required="service === '{{ $svc['slug'] }}'" class="w-full rounded-lg border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm placeholder-zinc-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30">{{ old('form_data.'.$field['name']) }}</textarea>
                                                        @break

                                                    @default
                                                        <input id="form_data_{{ $field['name'] }}" name="form_data[{{ $field['name'] }}]" type="{{ $field['type'] === 'number' ? 'number' : 'text' }}" @if ($field['type'] === 'number') min="1" step="1" inputmode="numeric" @endif placeholder="{{ $field['placeholder'] ?? '' }}" value="{{ old('form_data.'.$field['name']) }}" @required($required) :required="service === '{{ $svc['slug'] }}'" class="w-full rounded-lg border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm placeholder-zinc-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/30" />
                                                @endswitch

                                                @error($errorKey)
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </fieldset>
                            @endif
                        @endforeach

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