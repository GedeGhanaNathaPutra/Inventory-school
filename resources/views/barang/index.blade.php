<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Data Barang</h2>
    </x-slot>

    <div x-data="{ showCreate: false }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif

            <div class="glass-card">
                <div class="p-6">
                    @if (Auth::user()->role === 'ka_tu')
                        <button @@click="showCreate = true" class="inline-block mb-4 px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">+ Tambah Barang</button>
                    @endif

                    <form method="GET" class="mb-4 flex flex-wrap gap-2">
                        <input type="text" name="search" placeholder="Cari nama/kode/merek..." value="{{ request('search') }}" class="border rounded px-3 py-1 text-sm">
                        <select name="kategori" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Kategori</option>
                            <option value="bos" @selected(request('kategori') === 'bos')>BOS</option>
                            <option value="komite" @selected(request('kategori') === 'komite')>Komite</option>
                        </select>
                        <select name="jenis_barang" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Jenis</option>
                            <option value="inventaris" @selected(request('jenis_barang') === 'inventaris')>Inventaris</option>
                            <option value="non_inventaris" @selected(request('jenis_barang') === 'non_inventaris')>Non Inventaris</option>
                        </select>
                        <select name="kondisi" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Kondisi</option>
                            <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                            <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                            <option value="rusak_sedang" @selected(request('kondisi') === 'rusak_sedang')>Rusak Sedang</option>
                            <option value="rusak_berat" @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
                        </select>
                        <select name="ruangan_id" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Ruangan</option>
                            @foreach ($ruangans as $r)
                                <option value="{{ $r->id }}" @selected(request('ruangan_id') == $r->id)>{{ $r->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="tahun" placeholder="Tahun" value="{{ request('tahun') }}" class="border rounded px-3 py-1 text-sm w-20">
                        <button type="submit" class="px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">Filter</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="py-2 px-1">Kode</th>
                                    <th class="py-2 px-1">Nama Barang</th>
                                    <th class="py-2 px-1">Kategori</th>
                                    <th class="py-2 px-1">Jenis</th>
                                    <th class="py-2 px-1">Qty</th>
                                    <th class="py-2 px-1">Kondisi</th>
                                    <th class="py-2 px-1">Ruangan</th>
                                    <th class="py-2 px-1">Status</th>
                                    <th class="py-2 px-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang as $b)
                                    <tr class="border-b hover:bg-muted">
                                        <td class="py-2 px-1 font-mono">{{ $b->kode_barang }}</td>
                                        <td class="py-2 px-1">{{ $b->nama_barang }}</td>
                                        <td class="py-2 px-1 uppercase">{{ $b->kategori }}</td>
                                        <td class="py-2 px-1">{{ str_replace('_', ' ', $b->jenis_barang) }}</td>
                                        <td class="py-2 px-1">{{ $b->kuantitas }} {{ $b->nama_satuan }}</td>
                                        <td class="py-2 px-1">{{ str_replace('_', ' ', $b->kondisi) }}</td>
                                        <td class="py-2 px-1">{{ $b->ruangan?->nama_ruangan ?? '-' }}</td>
                                        <td class="py-2 px-1">{{ $b->status }}</td>
                                        <td class="py-2 px-1">
                                            <a href="{{ route('barang.show', $b) }}" class="text-primary hover:underline">Detail</a>
                                            @if (Auth::user()->role === 'ka_tu' && $b->status === 'aktif')
                                                <a href="{{ route('barang.edit', $b) }}" class="ml-2 text-warning hover:underline">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-4 text-center text-muted-foreground">Belum ada data barang.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $barang->links() }}</div>
                </div>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-show="showCreate" class="fixed inset-0 z-50 flex items-start justify-center px-4 py-10 sm:px-0" x-cloak>
            <div class="fixed inset-0 bg-black/50" @@click="showCreate = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold mb-4">Tambah Barang</h3>
                <form method="POST" action="{{ route('barang.store') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Tanggal Pembukuan</label>
                            <input type="date" name="tanggal_pembukuan" value="{{ old('tanggal_pembukuan', now()->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            @error('tanggal_pembukuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kategori</label>
                            <select name="kategori" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="bos" @selected(old('kategori') === 'bos')>BOS</option>
                                <option value="komite" @selected(old('kategori') === 'komite')>Komite</option>
                            </select>
                            @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium">Nama Barang</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            @error('nama_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Merek/Type</label>
                            <input type="text" name="merek_type" value="{{ old('merek_type') }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('merek_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Keterangan Nomor/Ukuran</label>
                            <input type="text" name="keterangan_nomor_ukuran" value="{{ old('keterangan_nomor_ukuran') }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('keterangan_nomor_ukuran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kuantitas</label>
                            <input type="number" name="kuantitas" value="{{ old('kuantitas', 1) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" min="1" required>
                            @error('kuantitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Satuan</label>
                            <input type="text" name="nama_satuan" value="{{ old('nama_satuan') }}" placeholder="pcs/unit/rim/set" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            @error('nama_satuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Jenis Barang</label>
                            <select name="jenis_barang" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="inventaris" @selected(old('jenis_barang') === 'inventaris')>Inventaris</option>
                                <option value="non_inventaris" @selected(old('jenis_barang') === 'non_inventaris')>Non Inventaris</option>
                            </select>
                            @error('jenis_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kelengkapan Dokumen</label>
                            <input type="text" name="kelengkapan_dokumen" value="{{ old('kelengkapan_dokumen') }}" placeholder="nota, faktur, garansi" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('kelengkapan_dokumen') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kondisi</label>
                            <select name="kondisi" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="baik" @selected(old('kondisi') === 'baik')>Baik</option>
                                <option value="rusak_ringan" @selected(old('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                                <option value="rusak_sedang" @selected(old('kondisi') === 'rusak_sedang')>Rusak Sedang</option>
                                <option value="rusak_berat" @selected(old('kondisi') === 'rusak_berat')>Rusak Berat</option>
                            </select>
                            @error('kondisi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Harga (Rp)</label>
                            <input type="number" step="0.01" name="harga" value="{{ old('harga') }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Ruangan</label>
                            <select name="ruangan_id" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruangans as $r)
                                    <option value="{{ $r->id }}" @selected(old('ruangan_id') == $r->id)>{{ $r->nama_ruangan }}</option>
                                @endforeach
                            </select>
                            @error('ruangan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium">Keterangan</label>
                            <textarea name="keterangan" class="w-full border rounded px-3 py-2 mt-1 text-sm" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Simpan</button>
                        <button type="button" @@click="showCreate = false" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
