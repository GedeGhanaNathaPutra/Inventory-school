@props(['title' => ''])

<div class="glass-card">
    <div class="px-4 py-3 border-b border-glass-border">
        <h3 class="font-semibold text-foreground">{{ $title }}</h3>
    </div>
    <div class="p-4">
        {{ $slot }}
    </div>
</div>
