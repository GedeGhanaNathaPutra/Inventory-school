@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2.5 text-sm font-medium text-foreground bg-primary/10 border-l-4 border-primary transition duration-150 ease-in-out'
            : 'block w-full px-4 py-2.5 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent border-l-4 border-transparent hover:border-accent transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
