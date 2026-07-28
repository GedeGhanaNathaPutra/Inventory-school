<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('barang.update', $barang) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Tanggal Pembukuan</label>
                            <input type="date" name="tanggal_pembukuan" value="{{ old('tanggal_pembukuan', $barang->tanggal_pembukuan) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            @error('tanggal_pembukuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kategori</label>
                            <select name="kategori" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="bos" @selected(old('kategori', $barang->kategori) === 'bos')>BOS</option>
                                <option value="komite" @selected(old('kategori', $barang->kategori) === 'komite')>Komite</option>
                            </select>
                            @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium">Nama Barang</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            @error('nama_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Merek/Type</label>
                            <input type="text" name="merek_type" value="{{ old('merek_type', $barang->merek_type) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('merek_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Keterangan Nomor/Ukuran</label>
                            <input type="text" name="keterangan_nomor_ukuran" value="{{ old('keterangan_nomor_ukuran', $barang->keterangan_nomor_ukuran) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('keterangan_nomor_ukuran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kuantitas</label>
                            <input type="number" name="kuantitas" value="{{ old('kuantitas', $barang->kuantitas) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" min="1" required>
                            @error('kuantitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Satuan</label>
                            <input type="text" name="nama_satuan" value="{{ old('nama_satuan', $barang->nama_satuan) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            @error('nama_satuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Jenis Barang</label>
                            <select name="jenis_barang" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="inventaris" @selected(old('jenis_barang', $barang->jenis_barang) === 'inventaris')>Inventaris</option>
                                <option value="non_inventaris" @selected(old('jenis_barang', $barang->jenis_barang) === 'non_inventaris')>Non Inventaris</option>
                            </select>
                            @error('jenis_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kelengkapan Dokumen</label>
                            <input type="text" name="kelengkapan_dokumen" value="{{ old('kelengkapan_dokumen', $barang->kelengkapan_dokumen) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('kelengkapan_dokumen') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kondisi</label>
                            <select name="kondisi" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="baik" @selected(old('kondisi', $barang->kondisi) === 'baik')>Baik</option>
                                <option value="rusak_ringan" @selected(old('kondisi', $barang->kondisi) === 'rusak_ringan')>Rusak Ringan</option>
                                <option value="rusak_sedang" @selected(old('kondisi', $barang->kondisi) === 'rusak_sedang')>Rusak Sedang</option>
                                <option value="rusak_berat" @selected(old('kondisi', $barang->kondisi) === 'rusak_berat')>Rusak Berat</option>
                            </select>
                            @error('kondisi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Harga (Rp)</label>
                            <input type="number" step="0.01" name="harga" value="{{ old('harga', $barang->harga) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            @error('harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Ruangan</label>
                            <select name="ruangan_id" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruangans as $r)
                                    <option value="{{ $r->id }}" @selected(old('ruangan_id', $barang->ruangan_id) == $r->id)>{{ $r->nama_ruangan }}</option>
                                @endforeach
                            </select>
                            @error('ruangan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium">Keterangan</label>
                            <textarea name="keterangan" class="w-full border rounded px-3 py-2 mt-1 text-sm" rows="3">{{ old('keterangan', $barang->keterangan) }}</textarea>
                            @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
                        <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
