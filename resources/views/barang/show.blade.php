<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Detail Barang</h2>
    </x-slot>

    <div x-data="{ showKondisi: false }" class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card p-6">
                <table class="w-full text-sm">
                    <tr class="border-b"><td class="py-2 font-semibold w-48">Kode Barang</td><td class="py-2 font-mono">{{ $barang->kode_barang }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Tanggal Pembukuan</td><td class="py-2">{{ $barang->tanggal_pembukuan }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Nama Barang</td><td class="py-2">{{ $barang->nama_barang }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Keterangan Nomor/Ukuran</td><td class="py-2">{{ $barang->keterangan_nomor_ukuran ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Merek/Type</td><td class="py-2">{{ $barang->merek_type ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kuantitas</td><td class="py-2">{{ $barang->kuantitas }} {{ $barang->nama_satuan }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kategori</td><td class="py-2 uppercase">{{ $barang->kategori }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Jenis Barang</td><td class="py-2">{{ str_replace('_', ' ', $barang->jenis_barang) }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kelengkapan Dokumen</td><td class="py-2">{{ $barang->kelengkapan_dokumen ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kondisi</td><td class="py-2">{{ str_replace('_', ' ', $barang->kondisi) }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Harga</td><td class="py-2">{{ $barang->harga ? 'Rp ' . number_format($barang->harga, 2) : '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Ruangan</td><td class="py-2">{{ $barang->ruangan?->nama_ruangan ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Status</td><td class="py-2">{{ $barang->status }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Dicatat Oleh</td><td class="py-2">{{ $barang->dicatatOleh?->name ?? '-' }}</td></tr>
                    <tr><td class="py-2 font-semibold">Keterangan</td><td class="py-2">{{ $barang->keterangan ?? '-' }}</td></tr>
                </table>

                <div class="mt-6 flex flex-wrap gap-2">
                    @if (in_array(Auth::user()->role, ['ka_tu', 'waka_sarpras', 'ka_prodi']) && $barang->status === 'aktif')
                        <button @@click="showKondisi = true" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Lapor Kondisi</button>
                    @endif
                    <a href="{{ route('kondisi.history', $barang) }}" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Riwayat Kondisi</a>
                    @if (Auth::user()->role === 'ka_tu' && $barang->status === 'aktif')
                        <a href="{{ route('barang.edit', $barang) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                    @endif
                    <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Kembali</a>
                </div>
            </div>
        </div>

        {{-- Kondisi Create Modal --}}
        <div x-show="showKondisi" class="fixed inset-0 z-50 flex items-start justify-center px-4 py-10 sm:px-0" x-cloak>
            <div class="fixed inset-0 bg-black/50" @@click="showKondisi = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold mb-4">Lapor Kondisi Barang</h3>
                <p class="mb-4 text-sm">
                    Barang: <strong>{{ $barang->kode_barang }} — {{ $barang->nama_barang }}</strong><br>
                    Kondisi saat ini: <strong>{{ str_replace('_', ' ', $barang->kondisi) }}</strong>
                </p>
                <form method="POST" action="{{ route('kondisi.store', $barang) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Kondisi Baru</label>
                        <select name="kondisi" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            <option value="baik" @selected(old('kondisi') === 'baik')>Baik</option>
                            <option value="rusak_ringan" @selected(old('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                            <option value="rusak_sedang" @selected(old('kondisi') === 'rusak_sedang')>Rusak Sedang</option>
                            <option value="rusak_berat" @selected(old('kondisi') === 'rusak_berat')>Rusak Berat</option>
                        </select>
                        @error('kondisi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Keterangan</label>
                        <textarea name="keterangan" class="w-full border rounded px-3 py-2 mt-1 text-sm" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Tanggal Lapor</label>
                        <input type="date" name="tanggal_lapor" value="{{ old('tanggal_lapor', now()->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('tanggal_lapor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <hr class="my-4">
                    <p class="text-sm text-gray-600 mb-3">
                        <strong>Upload Foto</strong> — Foto 3 arah <span class="text-red-500">wajib</span> jika kondisi bukan "Baik".
                    </p>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium">Foto Arah 1</label>
                            <input type="file" name="foto_1" accept="image/*" class="w-full mt-1 text-sm">
                            @error('foto_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Foto Arah 2</label>
                            <input type="file" name="foto_2" accept="image/*" class="w-full mt-1 text-sm">
                            @error('foto_2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Foto Arah 3</label>
                            <input type="file" name="foto_3" accept="image/*" class="w-full mt-1 text-sm">
                            @error('foto_3') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Simpan</button>
                        <button type="button" @@click="showKondisi = false" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
