@props(['tone' => 'brand', 'class' => 'size-full'])
@php
    $accent = $tone === 'flow' ? '#22d3ee' : '#6366f1';
    $accentStrong = $tone === 'flow' ? '#06b6d4' : '#4f46e5';
    $grad = 'grad-net-' . $tone;
@endphp
<svg viewBox="0 0 400 320" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    <defs>
        <linearGradient id="{{ $grad }}" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="{{ $accent }}" />
            <stop offset="1" stop-color="{{ $accentStrong }}" />
        </linearGradient>
    </defs>

    {{-- Réseau décoratif --}}
    <g stroke="currentColor" stroke-opacity="0.22" stroke-width="1.5">
        <path d="M60 285 C 90 200, 160 160, 196 150" fill="none" stroke-dasharray="3 7" />
        <path d="M308 285 C 300 170, 268 162, 254 180" fill="none" stroke-dasharray="3 7" />
    </g>

    {{-- Dalle de bureau --}}
    <rect x="32" y="286" width="336" height="10" rx="5" fill="currentColor" fill-opacity="0.12" />

    {{-- Ondes Wi-Fi --}}
    <g stroke="{{ $accent }}" fill="none" stroke-width="3.5" stroke-linecap="round">
        <path d="M182 158 Q 200 138 218 158" />
        <path d="M166 158 Q 200 122 234 158" stroke-opacity="0.6" />
        <path d="M150 158 Q 200 106 250 158" stroke-opacity="0.35" />
    </g>
    <circle cx="200" cy="140" r="4" fill="{{ $accent }}" />

    {{-- Routeur / switch --}}
    <g>
        <rect x="120" y="214" width="160" height="44" rx="10" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="232" y="178" width="7" height="36" rx="3.5" fill="currentColor" fill-opacity="0.8" />
        <rect x="148" y="178" width="7" height="36" rx="3.5" fill="currentColor" fill-opacity="0.8" />
        <rect x="228" y="170" width="15" height="14" rx="4" fill="{{ $accent }}" fill-opacity="0.85" />
        <rect x="144" y="170" width="15" height="14" rx="4" fill="url(#{{ $grad }})" />
        <circle cx="136" cy="232" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="152" cy="232" r="3" fill="{{ $accent }}" />
        <circle cx="168" cy="232" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="184" cy="232" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="200" cy="232" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="216" cy="232" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="232" cy="232" r="3" fill="currentColor" fill-opacity="0.35" />
        <circle cx="248" cy="232" r="3" fill="currentColor" fill-opacity="0.35" />
    </g>

    {{-- Connexions --}}
    <g stroke="{{ $accent }}" stroke-opacity="0.55" stroke-width="2" stroke-linecap="round" fill="none">
        <path d="M120 226 C 110 202, 98 206, 82 212" stroke-dasharray="2 6" />
        <path d="M280 226 C 306 194, 314 204, 328 208" stroke-dasharray="2 6" />
    </g>

    {{-- Smartphone --}}
    <g>
        <rect x="54" y="186" width="42" height="72" rx="12" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="62" y="194" width="26" height="52" rx="5" fill="currentColor" fill-opacity="0.2" />
        <circle cx="75" cy="252" r="2.5" fill="{{ $accent }}" />
    </g>

    {{-- Ordinateur portable --}}
    <g>
        <rect x="278" y="168" width="78" height="52" rx="8" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="288" y="178" width="58" height="30" rx="4" fill="currentColor" fill-opacity="0.22" />
        <rect x="288" y="186" width="30" height="5" rx="2.5" fill="{{ $accent }}" />
        <rect x="288" y="196" width="20" height="5" rx="2.5" fill="currentColor" fill-opacity="0.35" />
        <path d="M272 220 h90 l-12 -14 h-66 z" fill="currentColor" fill-opacity="0.14" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
    </g>

    {{-- Nuage (accès distant) --}}
    <g transform="translate(-16 30)">
        <path d="M84 40 h34 a14 14 0 0 1 0 28 H72 a10 10 0 0 1 -2 -20 h2 a12 12 0 0 1 12 -8 z" fill="currentColor" fill-opacity="0.10" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <path d="M112 54 l5 5 l9 -9" stroke="{{ $accent }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
    </g>

    {{-- Badge flottant (signal) --}}
    <g transform="translate(256 70)">
        <rect width="92" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
        <path d="M14 18 v-8" stroke="{{ $accent }}" stroke-width="4" stroke-linecap="round" />
        <path d="M24 18 v-12" stroke="currentColor" stroke-opacity="0.5" stroke-width="4" stroke-linecap="round" />
        <path d="M34 18 v-6" stroke="currentColor" stroke-opacity="0.5" stroke-width="4" stroke-linecap="round" />
        <path d="M44 18 v-10" stroke="currentColor" stroke-opacity="0.5" stroke-width="4" stroke-linecap="round" />
        <path d="M54 18 v-7" stroke="currentColor" stroke-opacity="0.5" stroke-width="4" stroke-linecap="round" />
    </g>

    {{-- Badge flottant (supervision) --}}
    <rect x="48" y="66" width="110" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
    <circle cx="64" cy="79" r="4" fill="{{ $accent }}" />
    <rect x="74" y="76" width="58" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
    <rect x="74" y="85" width="38" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
</svg>