@props(['tone' => 'brand', 'class' => 'size-full'])
@php
    $accent = $tone === 'flow' ? '#22d3ee' : '#6366f1';
    $accentStrong = $tone === 'flow' ? '#06b6d4' : '#4f46e5';
    $grad = 'grad-vt-' . $tone;
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
        <path d="M60 285 C 90 190, 150 150, 190 132" fill="none" stroke-dasharray="3 7" />
        <path d="M306 285 C 300 170, 276 160, 258 182" fill="none" stroke-dasharray="3 7" />
    </g>

    {{-- Dalle de bureau --}}
    <rect x="32" y="286" width="336" height="10" rx="5" fill="currentColor" fill-opacity="0.12" />

    {{-- Poste installé --}}
    <g>
        <rect x="46" y="112" width="162" height="126" rx="14" fill="currentColor" fill-opacity="0.08" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="57" y="123" width="140" height="92" rx="8" fill="#0b1020" />
        {{-- Config / checklist à l'écran --}}
        <rect x="70" y="139" width="46" height="7" rx="3.5" fill="currentColor" fill-opacity="0.25" />
        <rect x="70" y="154" width="34" height="7" rx="3.5" fill="currentColor" fill-opacity="0.25" />
        <rect x="70" y="169" width="42" height="7" rx="3.5" fill="currentColor" fill-opacity="0.25" />
        <rect x="70" y="184" width="26" height="7" rx="3.5" fill="{{ $accent }}" fill-opacity="0.9" />
        <circle cx="172" cy="150" r="18" fill="url(#{{ $grad }})" fill-opacity="0.95" />
        <path d="M165 150 l5 5 l13 -13" stroke="#0b1020" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M57 202 h140 l-12 -16 H69 z" fill="currentColor" fill-opacity="0.14" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="112" y="208" width="34" height="8" rx="4" fill="currentColor" fill-opacity="0.25" />
    </g>

    {{-- Traînée de livraison (vers le poste) --}}
    <path d="M300 182 C 264 148, 232 156, 208 172" stroke="{{ $accent }}" stroke-opacity="0.6" stroke-width="2" stroke-linecap="round" stroke-dasharray="2 6" />

    {{-- Carton de livraison --}}
    <g>
        <rect x="184" y="196" width="112" height="88" rx="10" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <rect x="184" y="192" width="112" height="6" rx="3" fill="{{ $accent }}" fill-opacity="0.8" />
        <path d="M188 192 l-14 -16 h38 l-11 16 z" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
        <path d="M292 192 l14 -16 h-38 l11 16 z" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
        {{-- Équipement neuf dans le carton --}}
        <rect x="206" y="210" width="44" height="34" rx="6" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-opacity="0.3" stroke-width="1.5" />
        <rect x="212" y="216" width="32" height="20" rx="3" fill="#0b1020" />
        <rect x="218" y="222" width="18" height="3" rx="1.5" fill="{{ $accent }}" />
        <rect x="270" y="222" width="22" height="16" rx="4" fill="currentColor" fill-opacity="0.18" stroke="currentColor" stroke-opacity="0.3" stroke-width="1.5" />
    </g>

    {{-- Sac de course --}}
    <g>
        <rect x="306" y="176" width="56" height="94" rx="10" fill="#0b1020" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.5" />
        <path d="M316 180 a18 18 0 0 1 36 0" stroke="currentColor" stroke-opacity="0.6" stroke-width="5" fill="none" stroke-linecap="round" />
        <rect x="306" y="222" width="56" height="7" rx="3.5" fill="{{ $accent }}" fill-opacity="0.85" />
        <rect x="306" y="236" width="56" height="7" rx="3.5" fill="{{ $accent }}" fill-opacity="0.55" />
        {{-- Étiquette de prix --}}
        <rect x="318" y="240" width="24" height="16" rx="3" fill="url(#{{ $grad }})" />
        <circle cx="330" cy="244" r="2.5" fill="#0b1020" />
        <rect x="324" y="250" width="12" height="2.5" rx="1.25" fill="#0b1020" fill-opacity="0.7" />
    </g>

    {{-- Code-barres --}}
    <g transform="translate(312 258)" fill="currentColor">
        <rect x="0" y="0" width="3" height="12" rx="1" fill-opacity="0.4" />
        <rect x="6" y="0" width="2" height="12" rx="1" fill-opacity="0.25" />
        <rect x="10" y="0" width="4" height="12" rx="1" fill-opacity="0.4" />
        <rect x="17" y="0" width="2" height="12" rx="1" fill-opacity="0.25" />
        <rect x="21" y="0" width="3" height="12" rx="1" fill-opacity="0.4" />
        <rect x="27" y="0" width="2" height="12" rx="1" fill-opacity="0.25" />
    </g>

    {{-- Étiquette flottante (devis) --}}
    <g transform="rotate(-8 318 92)">
        <rect x="286" y="70" width="64" height="46" rx="8" fill="url(#{{ $grad }})" />
        <circle cx="308" cy="80" r="4" fill="#0b1020" />
        <rect x="300" y="92" width="38" height="5" rx="2.5" fill="#0b1020" fill-opacity="0.7" />
        <rect x="300" y="102" width="26" height="4" rx="2" fill="#0b1020" fill-opacity="0.45" />
        <path d="M350 80 l12 -12" stroke="{{ $accent }}" stroke-width="4" stroke-linecap="round" />
    </g>

    {{-- Badge flottant --}}
    <rect x="50" y="66" width="112" height="26" rx="13" fill="{{ $accentStrong }}" fill-opacity="0.18" stroke="{{ $accent }}" stroke-opacity="0.6" />
    <circle cx="66" cy="79" r="4" fill="{{ $accent }}" />
    <rect x="76" y="76" width="60" height="5" rx="2.5" fill="currentColor" fill-opacity="0.45" />
    <rect x="76" y="85" width="40" height="4" rx="2" fill="currentColor" fill-opacity="0.25" />
</svg>