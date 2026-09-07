@props(['status'])
@php
    $styles = [
        'open' => 'bg-brand-50 text-brand-700',
        'new' => 'bg-brand-50 text-brand-700',
        'assigned' => 'bg-flow-50 text-flow-700',
        'in_progress' => 'bg-amber-50 text-amber-700',
        'pending' => 'bg-orange-50 text-orange-700',
        'resolved' => 'bg-emerald-50 text-emerald-700',
        'closed' => 'bg-zinc-100 text-zinc-500',
        'cancelled' => 'bg-zinc-100 text-zinc-500',
        'in_use' => 'bg-emerald-50 text-emerald-700',
        'in_stock' => 'bg-zinc-100 text-zinc-500',
        'repair' => 'bg-amber-50 text-amber-700',
        'retired' => 'bg-zinc-100 text-zinc-500',
    ];
    $class = $styles[$status] ?? 'bg-zinc-100 text-zinc-500';
@endphp
<span {{ $attributes->merge(['class' => 'badge '.$class]) }}>{{ str_replace('_', ' ', $status) }}</span>