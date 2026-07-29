@props(['label' => '', 'type' => 'primary'])

@php
$classes = [
    'primary' => 'bg-primary/10 text-primary',
    'success' => 'bg-success/10 text-success',
    'warning' => 'bg-warning/10 text-warning',
    'destructive' => 'bg-destructive/10 text-destructive',
];
$class = $classes[$type] ?? $classes['primary'];
@endphp

<span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $class }}">{{ $label }}</span>
