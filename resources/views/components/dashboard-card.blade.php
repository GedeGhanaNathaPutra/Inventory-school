@props(['label' => '', 'value' => '', 'color' => 'primary'])

@php
$colors = [
    'primary' => 'bg-primary text-primary-foreground',
    'success' => 'bg-success text-success-foreground',
    'warning' => 'bg-warning text-warning-foreground',
    'destructive' => 'bg-destructive text-destructive-foreground',
];
$bg = $colors[$color] ?? $colors['primary'];
@endphp

<div class="glass-card p-4 {{ $bg }} border-0">
    <div class="text-sm opacity-80">{{ $label }}</div>
    <div class="text-2xl font-bold mt-1">{{ $value }}</div>
</div>
