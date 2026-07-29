<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Riwayat Kondisi Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif

            <div class="glass-card p-6">
                <p class="mb-4 text-sm">
                    Barang: <strong>{{ $barang->kode_barang }} — {{ $barang->nama_barang }}</strong><br>
                    Kondisi saat ini: <strong>{{ str_replace('_', ' ', $barang->kondisi) }}</strong>
                </p>

                @if (in_array(Auth::user()->role, ['ka_tu', 'waka_sarpras', 'ka_prodi']))
                    <a href="{{ route('kondisi.create', $barang) }}" class="inline-block mb-4 px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">+ Laporkan Kondisi Baru</a>
                @endif

                @forelse ($histories as $h)
                    <div class="border rounded p-4 mb-3 @if ($loop->first) border-blue-300 bg-blue-50 @endif">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-semibold">{{ str_replace('_', ' ', $h->kondisi) }}</span>
                                <span class="text-sm text-muted-foreground ml-2">{{ $h->tanggal_lapor }}</span>
                            </div>
                            <div class="text-sm text-muted-foreground">oleh {{ $h->dilaporkanOleh?->name }}</div>
                        </div>
                        @if ($h->keterangan)
                            <p class="text-sm mt-1">{{ $h->keterangan }}</p>
                        @endif
                        @if ($h->foto_1 || $h->foto_2 || $h->foto_3)
                            <div class="flex gap-2 mt-2">
                                @foreach (['foto_1', 'foto_2', 'foto_3'] as $f)
                                    @if ($h->$f)
                                        <a href="{{ asset('storage/' . $h->$f) }}" target="_blank" class="text-primary text-sm hover:underline">{{ $f }}</a>
                                    @else
                                        <span class="text-gray-400 text-sm">{{ $f }} —</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted-foreground text-sm">Belum ada riwayat kondisi.</p>
                @endforelse

                <a href="{{ route('barang.show', $barang) }}" class="mt-4 inline-block px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent text-sm">Kembali ke Detail Barang</a>
            </div>
        </div>
    </div>
</x-app-layout>
