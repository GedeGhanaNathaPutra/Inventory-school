<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Laporan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('laporan.kategori') }}" class="glass-card p-6">
                    <h3 class="font-semibold text-lg">Per Kategori</h3>
                    <p class="text-sm text-muted-foreground">BOS / Komite</p>
                </a>
                <a href="{{ route('laporan.kondisi') }}" class="glass-card p-6">
                    <h3 class="font-semibold text-lg">Per Kondisi</h3>
                    <p class="text-sm text-muted-foreground">Baik / Rusak Ringan / Sedang / Berat</p>
                </a>
                <a href="{{ route('laporan.lokasi') }}" class="glass-card p-6">
                    <h3 class="font-semibold text-lg">Per Lokasi / Prodi</h3>
                    <p class="text-sm text-muted-foreground">Barang per ruangan & prodi</p>
                </a>
                <a href="{{ route('laporan.pengadaan') }}" class="glass-card p-6">
                    <h3 class="font-semibold text-lg">Status Pengadaan</h3>
                    <p class="text-sm text-muted-foreground">Progress alur pengajuan barang</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
