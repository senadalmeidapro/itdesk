@props(['tone' => 'brand', 'class' => 'size-full'])
@php
    $accent = $tone === 'flow' ? '#22d3ee' : '#6366f1';
    $accentStrong = $tone === 'flow' ? '#06b6d4' : '#4f46e5';
    $grad = 'grad-maint-' . $tone;
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
        <path d="M60 285 C 90 190, 160 150, 200 130" fill="none" stroke-dasharray="3 7" />
        <path d="M306 285 C 300 170, 272 160, 258 178" fill="none" stroke-dasharray="3 7" />
    </g>

    {{-- Dalle de bureau --}}
    <rect x="32" y="286" width="336" height="10" rx="5" fill="currentColor" fill-opacity="0.12" />

    {{-- Poste en cours de réparation --}}
    <g>
        <rect x="54" y="116" width="186" height="128" rx="14" fill="currentColor" fill-opacity="0.08" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="66" y="128" width="162" height="96" rx="8" fill="#0b1020" />
        {{-- Gribouille UI / diagnostic --}}
        <rect x="80" y="146" width="52" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
        <rect x="80" y="162" width="36" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
        <rect x="80" y="178" width="46" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
        <rect x="80" y="194" width="28" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
        {{-- Jauge "OK" --}}
        <circle cx="196" cy="154" r="20" fill="url(#{{ $grad }})" fill-opacity="0.95" />
        <path d="M188 154 l6 6 l14 -14" stroke="#0b1020" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
        {{-- Vis sur l'écran --}}
        <circle cx="236" cy="138" r="7" fill="{{ $accent }}" fill-opacity="0.9" />
        <path d="M232 138 h8" stroke="#0b1020" stroke-width="2" stroke-linecap="round" />
        {{-- Pied du poste --}}
        <path d="M66 224 h162 l-14 -18 H80 z" fill="currentColor" fill-opacity="0.14" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="134" y="230" width="30" height="8" rx="3" fill="currentColor" fill-opacity="0.25" />
    </g>

    {{-- Tournevis flottant --}}
    <g transform="rotate(48 314 118)">
        <rect x="-4" y="-64" width="8" height="78" rx="3" fill="currentColor" fill-opacity="0.7" />
        <path d="M-4 -64 l3 -16 h2 l3 16 z" fill="{{ $accent }}" />
        <rect x="-7" y="14" width="14" height="40" rx="6" fill="url(#{{ $grad }})" />
        <rect x="-7" y="50" width="14" height="5" rx="2.5" fill="{{ $accentStrong }}" />
    </g>

    {{-- Boîte à outils --}}
    <g>
        <path d="M282 208 v-12 a6 6 0 0 1 6 -6 h48 a6 6 0 0 1 6 6 v12" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-opacity="0.4" stroke-width="1.5" />
        <rect x="266" y="222" width="100" height="62" rx="12" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="282" y="238" width="30" height="6" rx="3" fill="{{ $accent }}" fill-opacity="0.9" />
        <rect x="318" y="238" width="34" height="6" rx="3" fill="currentColor" fill-opacity="0.3" />
        <rect x="282" y="254" width="46" height="6" rx="3" fill="currentColor" fill-opacity="0.3" />
        <rect x="334" y="254" width="20" height="6" rx="3" fill="currentColor" fill-opacity="0.18" />
        <path d="M302 238 v26" stroke="currentColor" stroke-opacity="0.25" stroke-width="2" />
    </g>

    {{-- Badge flottant --}}
    <rect x="52" y="70" width="108" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
    <circle cx="68" cy="83" r="4" fill="{{ $accent }}" />
    <rect x="78" y="80" width="58" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
    <rect x="78" y="89" width="38" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
</svg>