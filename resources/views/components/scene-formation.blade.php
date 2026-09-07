@props(['tone' => 'brand', 'class' => 'size-full'])
@php
    $accent = $tone === 'flow' ? '#22d3ee' : '#6366f1';
    $accentStrong = $tone === 'flow' ? '#06b6d4' : '#4f46e5';
    $grad = 'grad-fm-' . $tone;
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
        <path d="M60 285 C 90 190, 160 160, 200 140" fill="none" stroke-dasharray="3 7" />
        <path d="M306 285 C 300 170, 268 162, 252 180" fill="none" stroke-dasharray="3 7" />
    </g>

    {{-- Dalle de bureau --}}
    <rect x="32" y="286" width="336" height="10" rx="5" fill="currentColor" fill-opacity="0.12" />

    {{-- Tableau / écran de formation --}}
    <g>
        <rect x="52" y="110" width="184" height="126" rx="10" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="68" y="122" width="64" height="6" rx="3" fill="currentColor" fill-opacity="0.35" />
        {{-- Diagramme à barres --}}
        <rect x="74" y="184" width="18" height="32" rx="4" fill="currentColor" fill-opacity="0.3" />
        <rect x="100" y="168" width="18" height="48" rx="4" fill="currentColor" fill-opacity="0.3" />
        <rect x="126" y="154" width="18" height="62" rx="4" fill="url(#{{ $grad }})" />
        <rect x="152" y="176" width="18" height="40" rx="4" fill="currentColor" fill-opacity="0.3" />
        {{-- Anneau de progression --}}
        <path d="M202 166 a24 24 0 1 1 -23 12" stroke="{{ $accent }}" stroke-width="7" fill="none" stroke-linecap="round" />
        <path d="M178 190 a24 24 0 1 0 23 -12" stroke="currentColor" stroke-opacity="0.2" stroke-width="7" fill="none" stroke-linecap="round" />
        <circle cx="185" cy="190" r="4" fill="{{ $accent }}" />
        <circle cx="203" cy="166" r="9" fill="{{ $accent }}" fill-opacity="0.2" />
        {{-- Pieds / chevalet --}}
        <path d="M58 236 l-12 48" stroke="currentColor" stroke-opacity="0.35" stroke-width="2" stroke-linecap="round" />
        <path d="M230 236 l12 48" stroke="currentColor" stroke-opacity="0.35" stroke-width="2" stroke-linecap="round" />
    </g>

    {{-- Toque (diplôme) --}}
    <g transform="translate(162 44)">
        <circle cx="0" cy="14" r="3.5" fill="currentColor" fill-opacity="0.5" />
        <path d="M0 -2 L28 10 L0 22 L-28 10 z" fill="url(#{{ $grad }})" />
        <path d="M0 22 l0 22" stroke="currentColor" stroke-opacity="0.7" stroke-width="3" stroke-linecap="round" />
        <circle cx="0" cy="44" r="3" fill="{{ $accent }}" />
        <path d="M-6 50 h4 M-3 56 h8 M2 62 h6" stroke="{{ $accent }}" stroke-width="3" stroke-linecap="round" />
        <path d="M-10 10 l-6 5 l14 -2 -6 -5 z" fill="{{ $accent }}" />
    </g>

    {{-- Livre ouvert --}}
    <g>
        <path d="M322 176 L296 162 L290 194 L318 206 z" fill="#0b1020" stroke="currentColor" stroke-opacity="0.4" stroke-width="1.5" />
        <path d="M322 176 L348 162 L354 194 L326 206 z" fill="#0b1020" stroke="currentColor" stroke-opacity="0.4" stroke-width="1.5" />
        <path d="M322 176 L322 206" stroke="{{ $accent }}" stroke-width="3" stroke-linecap="round" />
        <rect x="298" y="172" width="16" height="4" rx="2" fill="currentColor" fill-opacity="0.3" />
        <rect x="296" y="184" width="20" height="4" rx="2" fill="currentColor" fill-opacity="0.3" />
        <rect x="338" y="172" width="14" height="4" rx="2" fill="currentColor" fill-opacity="0.3" />
        <rect x="342" y="184" width="10" height="4" rx="2" fill="currentColor" fill-opacity="0.3" />
        <rect x="296" y="224" width="52" height="8" rx="4" fill="currentColor" fill-opacity="0.3" />
    </g>

    {{-- Parcours d'apprentissage --}}
    <g stroke="{{ $accent }}" stroke-opacity="0.55" stroke-width="2" stroke-linecap="round" fill="none">
        <path d="M346 208 C 366 190, 366 170, 372 158" stroke-dasharray="2 6" />
    </g>
    <circle cx="348" cy="196" r="4" fill="{{ $accent }}" fill-opacity="0.5" />
    <circle cx="360" cy="176" r="4" fill="{{ $accent }}" fill-opacity="0.5" />
    <circle cx="376" cy="146" r="14" fill="url(#{{ $grad }})" fill-opacity="0.95" />
    <path d="M370 146 l4 4 l8 -8" stroke="#0b1020" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />

    {{-- Pastille 1:1 --}}
    <g transform="translate(268 88)">
        <rect width="104" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
        <circle cx="14" cy="13" r="3" fill="{{ $accent }}" />
        <rect x="22" y="10" width="52" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
        <rect x="22" y="19" width="36" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
    </g>

    {{-- Étincelles --}}
    <path d="M86 40 l4 8 8 4 -8 4 -4 8 -4 -8 -8 -4 8 -4 z" fill="{{ $accent }}" />
    <g transform="translate(118 64) scale(0.6)">
        <path d="M86 40 l4 8 8 4 -8 4 -4 8 -4 -8 -8 -4 8 -4 z" fill="{{ $accent }}" />
    </g>
</svg>