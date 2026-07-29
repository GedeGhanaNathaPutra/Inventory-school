<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Detail Serah Terima</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 px-4 py-2 bg-destructive/10 border border-destructive/30 text-destructive rounded">{{ session('error') }}</div>
            @endif

            <div class="glass-card p-6">
                <table class="w-full text-sm">
                    <tr class="border-b"><td class="py-2 font-semibold w-48">No. Berita Acara</td><td class="py-2 font-mono">{{ $serahTerima->nomor_berita_acara }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Tanggal</td><td class="py-2">{{ $serahTerima->tanggal_serah_terima }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Dari</td><td class="py-2">{{ $serahTerima->dariUser?->name }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kepada</td><td class="py-2">{{ $serahTerima->keUser?->name }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Ruangan Tujuan</td><td class="py-2">{{ $serahTerima->ruanganTujuan?->nama_ruangan ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Status</td><td class="py-2">{{ $serahTerima->status }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Catatan</td><td class="py-2">{{ $serahTerima->catatan ?? '-' }}</td></tr>
                </table>

                <h3 class="font-semibold mt-6 mb-2">Daftar Barang</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 px-1">Kode</th>
                            <th class="py-2 px-1">Nama Barang</th>
                            <th class="py-2 px-1">Jumlah</th>
                            <th class="py-2 px-1">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serahTerima->items as $item)
                            <tr class="border-b">
                                <td class="py-2 px-1 font-mono">{{ $item->barang?->kode_barang }}</td>
                                <td class="py-2 px-1">{{ $item->barang?->nama_barang }}</td>
                                <td class="py-2 px-1">{{ $item->jumlah }}</td>
                                <td class="py-2 px-1">{{ str_replace('_', ' ', $item->kondisi_saat_serah_terima) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 flex gap-2">
                    @if ($serahTerima->status === 'draft' && Auth::id() === $serahTerima->ke_user_id)
                        <form method="POST" action="{{ route('serah-terima.acknowledge', $serahTerima) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-success text-success-foreground rounded hover:bg-success/90" onclick="return confirm('Terima semua barang ini? Lokasi barang akan otomatis diperbarui.')">Terima Barang</button>
                        </form>
                    @endif
                    <a href="{{ route('serah-terima.pdf', $serahTerima) }}" class="px-4 py-2 bg-destructive text-destructive-foreground rounded hover:bg-destructive/90">Download PDF</a>
                    <a href="{{ route('serah-terima.index') }}" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
