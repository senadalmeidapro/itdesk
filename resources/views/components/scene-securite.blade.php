@props(['tone' => 'brand', 'class' => 'size-full'])
@php
    $accent = $tone === 'flow' ? '#22d3ee' : '#6366f1';
    $accentStrong = $tone === 'flow' ? '#06b6d4' : '#4f46e5';
    $grad = 'grad-sec-' . $tone;
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
        <path d="M60 285 C 90 190, 160 160, 196 140" fill="none" stroke-dasharray="3 7" />
        <path d="M306 285 C 300 170, 268 162, 254 182" fill="none" stroke-dasharray="3 7" />
    </g>

    {{-- Dalle de bureau --}}
    <rect x="32" y="286" width="336" height="10" rx="5" fill="currentColor" fill-opacity="0.12" />

    {{-- Bouclier + cadenas --}}
    <g>
        <path d="M158 96 l42 18 v34 c0 32 -19 54 -42 66 c-23 -12 -42 -34 -42 -66 v-34 z" fill="url(#{{ $grad }})" fill-opacity="0.2" stroke="{{ $accent }}" stroke-opacity="0.85" stroke-width="3" stroke-linejoin="round" />
        <path d="M142 150 v-6 a16 16 0 0 1 32 0 v6" fill="none" stroke="currentColor" stroke-opacity="0.9" stroke-width="5" stroke-linecap="round" />
        <rect x="138" y="150" width="40" height="30" rx="6" fill="#0b1020" stroke="currentColor" stroke-opacity="0.45" stroke-width="1.5" />
        <circle cx="158" cy="163" r="3.5" fill="{{ $accent }}" />
        <path d="M158 166 v6" stroke="{{ $accent }}" stroke-width="2.5" stroke-linecap="round" />
    </g>

    {{-- Nuage (sauvegarde cloud) --}}
    <g transform="translate(24 -4)">
        <path d="M160 44 h34 a14 14 0 0 1 0 28 h-34 a10 10 0 0 1 -2 -20 h2 a12 12 0 0 1 12 -8 z" fill="currentColor" fill-opacity="0.10" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <circle cx="180" cy="58" r="12" fill="url(#{{ $grad }})" fill-opacity="0.9" />
        <path d="M174 58 l4 4 l8 -8" stroke="#0b1020" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
    </g>

    {{-- Flèche de sauvegarde (cloud → baie) --}}
    <g stroke="{{ $accent }}" stroke-opacity="0.8" stroke-width="2.5" stroke-linecap="round" fill="none">
        <path d="M288 92 v70" stroke-dasharray="2 6" />
        <path d="M288 168 l-6 -9" />
        <path d="M288 168 l6 -9" />
    </g>

    {{-- Baie de sauvegarde (NAS) --}}
    <g>
        <rect x="252" y="188" width="86" height="32" rx="7" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <circle cx="264" cy="204" r="3" fill="{{ $accent }}" />
        <circle cx="275" cy="204" r="3" fill="currentColor" fill-opacity="0.4" />
        <rect x="290" y="201" width="34" height="6" rx="3" fill="currentColor" fill-opacity="0.3" />
        <rect x="252" y="230" width="86" height="32" rx="7" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <circle cx="264" cy="246" r="3" fill="currentColor" fill-opacity="0.4" />
        <circle cx="275" cy="246" r="3" fill="{{ $accent }}" />
        <rect x="290" y="243" width="34" height="6" rx="3" fill="currentColor" fill-opacity="0.3" />
        {{-- Validation (sauvegarde OK) --}}
        <circle cx="322" cy="246" r="10" fill="url(#{{ $grad }})" fill-opacity="0.95" />
        <path d="M316 246 l4 4 l9 -9" stroke="#0b1020" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
    </g>

    {{-- Badge flottant 3-2-1 --}}
    <rect x="52" y="60" width="92" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
    <circle cx="66" cy="73" r="4" fill="{{ $accent }}" />
    <rect x="76" y="70" width="50" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
    <rect x="76" y="79" width="32" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />

    {{-- Disque de sauvegarde flottant --}}
    <g transform="translate(238 96)">
        <circle cx="0" cy="0" r="14" fill="#0b1020" stroke="currentColor" stroke-opacity="0.4" stroke-width="1.5" />
        <circle cx="0" cy="0" r="4" fill="{{ $accent }}" />
    </g>

    {{-- Badge flottant (protection 24/7) --}}
    <g transform="translate(308 150)">
        <rect width="82" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
        <circle cx="14" cy="13" r="3" fill="{{ $accent }}" />
        <rect x="22" y="10" width="48" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
        <rect x="22" y="19" width="30" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
    </g>
</svg>