<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Stok Barang</h2>
    </x-slot>

    <div x-data="{ showCreate: false }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif

            @if (Auth::user()->role === 'ka_tu')
                <button @@click="showCreate = true" class="inline-block mb-4 px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">+ Tambah Mutasi</button>
            @endif

            <form method="GET" class="mb-4 flex gap-2">
                <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}" class="border rounded px-3 py-1 text-sm">
                <select name="kategori" class="border rounded px-3 py-1 text-sm">
                    <option value="">Semua</option>
                    <option value="bos" @selected(request('kategori') === 'bos')>BOS</option>
                    <option value="komite" @selected(request('kategori') === 'komite')>Komite</option>
                </select>
                <button type="submit" class="px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">Filter</button>
            </form>

            <div class="glass-card p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="py-2 px-1">Kode</th>
                                <th class="py-2 px-1">Nama Barang</th>
                                <th class="py-2 px-1">Stok Awal</th>
                                <th class="py-2 px-1">Masuk</th>
                                <th class="py-2 px-1">Keluar</th>
                                <th class="py-2 px-1">Stok Akhir</th>
                                <th class="py-2 px-1">Ruangan</th>
                                <th class="py-2 px-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangs as $b)
                                <tr class="border-b hover:bg-muted">
                                    <td class="py-2 px-1 font-mono">{{ $b->kode_barang }}</td>
                                    <td class="py-2 px-1">{{ $b->nama_barang }}</td>
                                    <td class="py-2 px-1">{{ $b->kuantitas }}</td>
                                    <td class="py-2 px-1 text-green-600">{{ $b->stok_masuk }}</td>
                                    <td class="py-2 px-1 text-red-600">{{ $b->stok_keluar }}</td>
                                    <td class="py-2 px-1 font-semibold">{{ $b->stok_akhir }}</td>
                                    <td class="py-2 px-1">{{ $b->ruangan?->nama_ruangan ?? '-' }}</td>
                                    <td class="py-2 px-1"><a href="{{ route('stok.show', $b) }}" class="text-primary hover:underline">Mutasi</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="py-4 text-center text-muted-foreground">Belum ada data barang.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $barangs->links() }}</div>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-show="showCreate" class="fixed inset-0 z-50 flex items-start justify-center px-4 py-10 sm:px-0" x-cloak>
            <div class="fixed inset-0 bg-black/50" @@click="showCreate = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Tambah Mutasi Stok</h3>
                <form method="POST" action="{{ route('stok.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Barang</label>
                        <select name="barang_id" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b->id }}" @selected(old('barang_id') == $b->id)>{{ $b->kode_barang }} — {{ $b->nama_barang }}</option>
                            @endforeach
                        </select>
                        @error('barang_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Jenis Mutasi</label>
                        <select name="jenis" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            <option value="masuk" @selected(old('jenis') === 'masuk')>Masuk</option>
                            <option value="keluar" @selected(old('jenis') === 'keluar')>Keluar</option>
                        </select>
                        @error('jenis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Jumlah</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" min="1" required>
                        @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Keterangan</label>
                        <textarea name="keterangan" class="w-full border rounded px-3 py-2 mt-1 text-sm" rows="2">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Simpan</button>
                        <button type="button" @@click="showCreate = false" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
