<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Mutasi Stok</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                        <a href="{{ route('stok.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
