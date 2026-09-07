@props(['tone' => 'brand', 'class' => 'size-full'])
@php
    $accent = $tone === 'flow' ? '#22d3ee' : '#6366f1';
    $accentStrong = $tone === 'flow' ? '#06b6d4' : '#4f46e5';
    $grad = 'grad-hd-' . $tone;
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
        <path d="M60 285 C 90 190, 160 170, 205 160" fill="none" stroke-dasharray="3 7" />
        <path d="M306 285 C 300 170, 268 170, 244 180" fill="none" stroke-dasharray="3 7" />
    </g>

    {{-- Dalle de bureau --}}
    <rect x="32" y="286" width="336" height="10" rx="5" fill="currentColor" fill-opacity="0.12" />

    {{-- Casque (helpdesk) --}}
    <g>
        <path d="M132 96 a68 68 0 0 1 136 0" stroke="{{ $accent }}" stroke-width="11" fill="none" stroke-linecap="round" />
        <rect x="112" y="94" width="46" height="82" rx="16" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <circle cx="135" cy="126" r="13" fill="url(#{{ $grad }})" fill-opacity="0.85" />
        <circle cx="135" cy="126" r="4" fill="#0b1020" fill-opacity="0.6" />
        <rect x="242" y="94" width="46" height="82" rx="16" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <circle cx="265" cy="126" r="13" fill="currentColor" fill-opacity="0.25" />
        {{-- Micro --}}
        <path d="M130 168 l-6 22" stroke="currentColor" stroke-opacity="0.55" stroke-width="6" stroke-linecap="round" />
        <rect x="116" y="184" width="12" height="26" rx="6" fill="url(#{{ $grad }})" />
    </g>

    {{-- Bulle de réponse (principale) --}}
    <g>
        <rect x="268" y="56" width="96" height="58" rx="14" fill="url(#{{ $grad }})" />
        <path d="M288 114 l8 12 v-12 z" fill="{{ $accentStrong }}" />
        <rect x="282" y="72" width="62" height="6" rx="3" fill="#0b1020" fill-opacity="0.85" />
        <rect x="282" y="84" width="42" height="6" rx="3" fill="#0b1020" fill-opacity="0.55" />
        <rect x="282" y="96" width="26" height="6" rx="3" fill="#0b1020" fill-opacity="0.55" />
    </g>

    {{-- Bulle confirmée --}}
    <g>
        <rect x="52" y="196" width="96" height="50" rx="12" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="66" y="214" width="44" height="5" rx="2.5" fill="currentColor" fill-opacity="0.4" />
        <rect x="66" y="224" width="28" height="5" rx="2.5" fill="currentColor" fill-opacity="0.25" />
        <circle cx="132" cy="221" r="12" fill="url(#{{ $grad }})" fill-opacity="0.95" />
        <path d="M126 221 l4 4 l8 -8" stroke="#0b1020" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
    </g>

    {{-- Éclair (réactivité) --}}
    <g>
        <rect x="52" y="104" width="68" height="28" rx="14" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
        <path d="M78 108 l-10 11 h7 l-6 9 11 -12 h-7 z" fill="{{ $accent }}" />
    </g>

    {{-- Pastille SLA --}}
    <g transform="translate(184 196)">
        <rect width="100" height="26" rx="13" fill="currentColor" fill-opacity="0.06" stroke="currentColor" stroke-opacity="0.3" stroke-width="1.5" />
        <circle cx="13" cy="13" r="6" fill="none" stroke="{{ $accent }}" stroke-width="2.5" />
        <path d="M13 10 v4 l3 2" stroke="{{ $accent }}" stroke-width="2" stroke-linecap="round" />
        <rect x="28" y="10" width="42" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
        <rect x="28" y="18" width="30" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
    </g>

    {{-- Badge flottant --}}
    <rect x="136" y="52" width="96" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
    <circle cx="152" cy="65" r="4" fill="{{ $accent }}" />
    <rect x="162" y="62" width="56" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
    <rect x="162" y="71" width="36" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
</svg>