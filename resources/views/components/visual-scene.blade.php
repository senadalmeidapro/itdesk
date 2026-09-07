@props(['tone' => 'brand', 'class' => 'size-full'])
@php
    $accent = $tone === 'flow' ? '#22d3ee' : '#6366f1';
    $accentStrong = $tone === 'flow' ? '#06b6d4' : '#4f46e5';
@endphp
<svg viewBox="0 0 400 320" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    <defs>
        <linearGradient id="vs-sw{{ $accentStrong }}" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="{{ $accent }}" />
            <stop offset="1" stop-color="{{ $accentStrong }}" />
        </linearGradient>
    </defs>

    {{-- Réseau décoratif --}}
    <g stroke="currentColor" stroke-opacity="0.22" stroke-width="1.5">
        <path d="M60 285 C 90 190, 170 150, 205 128" fill="none" stroke-dasharray="3 7" />
        <path d="M270 140 C 300 120, 340 118, 356 130" fill="none" stroke-dasharray="3 7" />
        <path d="M300 285 C 292 160, 268 152, 250 168" fill="none" stroke-dasharray="3 7" />
    </g>

    {{-- Dalle de bureau --}}
    <rect x="32" y="286" width="336" height="10" rx="5" fill="currentColor" fill-opacity="0.12" />

    {{-- Poste de travail (écran + base) --}}
    <g>
        <rect x="56" y="120" width="168" height="118" rx="14" fill="currentColor" fill-opacity="0.08" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="66" y="130" width="148" height="98" rx="8" fill="#0b1020" />
        {{-- fond gribouille de l'écran (visuels UI) --}}
        <rect x="78" y="146" width="56" height="8" rx="4" fill="{{ $accent }}" fill-opacity="0.9" />
        <rect x="78" y="162" width="34" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
        <rect x="78" y="178" width="46" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
        <rect x="78" y="194" width="30" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
        <circle cx="196" cy="150" r="14" fill="url(#vs-sw{{ $accentStrong }})" fill-opacity="0.95" />
        <path d="M190 150 l4.5 4.5 L202 143" stroke="#0b1020" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M110 214 h90" stroke="currentColor" stroke-opacity="0.25" stroke-width="2" stroke-linecap="round" />
        <g transform="translate(82 214)">
            <circle cx="0" cy="0" r="3" fill="currentColor" fill-opacity="0.35" />
            <circle cx="12" cy="0" r="3" fill="{{ $accent }}" />
            <circle cx="24" cy="0" r="3" fill="currentColor" fill-opacity="0.35" />
        </g>
        <path d="M68 238 h144 l-14 -16 h-116 z" fill="currentColor" fill-opacity="0.14" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="134" y="242" width="28" height="8" rx="3" fill="currentColor" fill-opacity="0.25" />
    </g>

    {{-- Rack serveurs + switch --}}
    <g>
        <rect x="272" y="132" width="72" height="124" rx="12" fill="currentColor" fill-opacity="0.08" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="284" y="148" width="48" height="18" rx="5" fill="currentColor" fill-opacity="0.18" />
        <circle cx="292" cy="157" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="302" cy="157" r="3" fill="{{ $accent }}" />
        <rect x="284" y="176" width="48" height="18" rx="5" fill="currentColor" fill-opacity="0.18" />
        <circle cx="292" cy="185" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="302" cy="185" r="3" fill="{{ $accent }}" />
        <rect x="284" y="204" width="48" height="18" rx="5" fill="currentColor" fill-opacity="0.18" />
        <circle cx="292" cy="213" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="302" cy="213" r="3" fill="currentColor" fill-opacity="0.35" />
        <rect x="292" y="234" width="32" height="10" rx="4" fill="currentColor" fill-opacity="0.25" />
    </g>

    {{-- Badges flottants --}}
    <g>
        <rect x="300" y="86" width="76" height="22" rx="11" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
        <circle cx="312" cy="97" r="3" fill="{{ $accent }}" />
        <rect x="320" y="94" width="40" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
        <rect x="320" y="102" width="26" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
    </g>
    <g transform="translate(48 94)">
        <path d="M0 14 a14 14 0 1 1 28 0" fill="none" stroke="{{ $accent }}" stroke-opacity="0.9" stroke-width="3" stroke-linecap="round" />
        <path d="M6 14 a8 8 0 1 1 16 0" fill="none" stroke="{{ $accent }}" stroke-opacity="0.55" stroke-width="3" stroke-linecap="round" />
        <circle cx="14" cy="14" r="3" fill="{{ $accent }}" />
    </g>

    {{-- Nuage + validation --}}
    <g>
        <path d="M84 40 h34 a14 14 0 0 1 0 28 H72 a10 10 0 0 1 -2 -20 h2 a12 12 0 0 1 12 -8 z" fill="currentColor" fill-opacity="0.10" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <circle cx="232" cy="40" r="20" fill="url(#vs-sw{{ $accentStrong }})" fill-opacity="0.9" />
        <path d="M225 40 l5 5 l11 -11" stroke="#0b1020" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
    </g>
</svg>