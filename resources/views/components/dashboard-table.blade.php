@props(['headers' => []])

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-border text-left text-muted-foreground">
                @foreach ($headers as $h)
                    <th class="py-2 px-1 font-medium">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
