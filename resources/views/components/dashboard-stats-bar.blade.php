@props(['label' => '', 'value' => 0, 'color' => 'primary', 'total' => 1])

@php
$colors = [
    'primary' => 'bg-primary',
    'success' => 'bg-success',
    'warning' => 'bg-warning',
    'destructive' => 'bg-destructive',
];
$bar = $colors[$color] ?? $colors['primary'];
$pct = $total > 0 ? round(($value / $total) * 100) : 0;
@endphp

<div class="mb-3">
    <div class="flex justify-between text-sm mb-1">
        <span class="text-foreground">{{ $label }}</span>
        <span class="text-foreground font-medium">{{ $value }} ({{ $pct }}%)</span>
    </div>
    <div class="w-full bg-muted rounded-full h-2">
        <div class="{{ $bar }} h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
    </div>
</div>
