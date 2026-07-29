<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Kartu Inventaris Ruangan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (Auth::user()->role !== 'ka_prodi')
                <form method="GET" class="mb-4 flex gap-2">
                    <select name="prodi_id" class="border rounded px-3 py-1 text-sm">
                        <option value="">Semua Prodi</option>
                        @foreach ($prodis as $p)
                            <option value="{{ $p->id }}" @selected(request('prodi_id') == $p->id)>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">Filter</button>
                </form>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($ruangans as $r)
                    <a href="{{ route('kartu.show', $r) }}" class="glass-card p-4 hover:shadow-md transition block">
                        <h3 class="font-semibold">{{ $r->nama_ruangan }}</h3>
                        <p class="text-sm text-muted-foreground">{{ $r->prodi?->nama_prodi ?? 'Umum' }}</p>
                        <p class="text-xs text-gray-400 mt-1">Klik untuk detail</p>
                    </a>
                @empty
                    <p class="text-muted-foreground col-span-3">Tidak ada ruangan.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
