<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Laporan Per Kondisi</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-4 flex gap-2 items-end">
                <select name="kondisi" class="border rounded px-3 py-1 text-sm">
                    <option value="">Semua</option>
                    <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                    <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                    <option value="rusak_sedang" @selected(request('kondisi') === 'rusak_sedang')>Rusak Sedang</option>
                    <option value="rusak_berat" @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
                </select>
                <button type="submit" class="px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">Tampilkan</button>
                <button type="submit" name="export" value="pdf" class="px-3 py-1 bg-destructive text-destructive-foreground rounded text-sm hover:bg-destructive/90">PDF</button>
                <button type="submit" name="export" value="excel" class="px-3 py-1 bg-success text-success-foreground rounded text-sm hover:bg-success/90">Excel</button>
            </form>

            @foreach ($data as $kondisi => $items)
                <div class="glass-card p-4 mb-4">
                    <h3 class="font-semibold mb-2">{{ str_replace('_', ' ', $kondisi) }} ({{ $items->count() }} item)</h3>
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-left"><th class="py-1">Kode</th><th class="py-1">Nama</th><th class="py-1">Kategori</th><th class="py-1">Qty</th><th class="py-1">Ruangan</th></tr></thead>
                        <tbody>
                            @foreach ($items as $b)
                                <tr class="border-b"><td class="py-1 font-mono">{{ $b->kode_barang }}</td><td class="py-1">{{ $b->nama_barang }}</td><td class="py-1 uppercase">{{ $b->kategori }}</td><td class="py-1">{{ $b->kuantitas }}</td><td class="py-1">{{ $b->ruangan?->nama_ruangan ?? '-' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
